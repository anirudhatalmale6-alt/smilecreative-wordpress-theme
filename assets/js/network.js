/**
 * Cursor-reactive map of where the work is.
 *
 * Same technique as the reference (one canvas, no library, nothing loaded from
 * anywhere, no tracking) but every point is a real client location, so the
 * motion carries information instead of being decoration.
 *
 * Honours prefers-reduced-motion and stops entirely when the tab is hidden.
 */
(function(){
  var canvas=document.getElementById('field');
  if(!canvas || !canvas.getContext) { return; }   // hero turned off in the Customizer
  var ctx=canvas.getContext('2d');
  var readout=document.getElementById('readout');
  var reduce=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var DPR=Math.min(window.devicePixelRatio||1,2), W=0,H=0,raf=null,visible=true;

  /* Places come from PHP (inc/helpers.php -> sc_places), so the list is data
     rather than something buried in a script. Laid out as a stylised map, not
     a true projection -- Port Louis is 10,000km away and a real projection
     would squash the UK into a thumbnail. */
  var PLACES = (window.SC_PLACES && window.SC_PLACES.places) ? window.SC_PLACES.places : [];
  if (!PLACES.length) { return; }

  var pts=[], mouse={x:-9999,y:-9999,on:false}, hoverIdx=-1;
  var LINK=260, HOVER=110;

  function resize(){
    var r=canvas.getBoundingClientRect();
    W=canvas.width=Math.floor(r.width*DPR); H=canvas.height=Math.floor(r.height*DPR);
    pts=PLACES.map(function(p,i){
      return {bx:p.x*W, by:p.y*H, x:p.x*W, y:p.y*H,
              ph:Math.random()*Math.PI*2, sp:0.25+Math.random()*0.35,
              amp:(5+Math.random()*7)*DPR, r:p.r*DPR, i:i};
    });
  }

  function frame(t){
    ctx.clearRect(0,0,W,H);
    var time=(t||0)/1000;

    // drift: a slow breath, so the field is alive without being busy
    for(var i=0;i<pts.length;i++){
      var p=pts[i];
      p.x=p.bx+Math.cos(time*p.sp+p.ph)*p.amp;
      p.y=p.by+Math.sin(time*p.sp*0.8+p.ph)*p.amp*0.7;
    }

    // find the nearest node to the cursor
    hoverIdx=-1;
    if(mouse.on){
      var best=HOVER*DPR;
      for(var h=0;h<pts.length;h++){
        var d=Math.hypot(pts[h].x-mouse.x, pts[h].y-mouse.y);
        if(d<best){best=d;hoverIdx=h;}
      }
    }

    // lines home to Belfast -- the structure means something: one studio, nine places
    var home=pts[0];
    for(var a=1;a<pts.length;a++){
      var lit=(hoverIdx===a||hoverIdx===0);
      ctx.beginPath(); ctx.moveTo(home.x,home.y); ctx.lineTo(pts[a].x,pts[a].y);
      ctx.strokeStyle=lit?'rgba(255,219,0,.55)':'rgba(255,219,0,.16)';
      ctx.lineWidth=(lit?1.5:0.9)*DPR; ctx.stroke();
    }
    // near neighbours, faint, for texture only
    for(var b=1;b<pts.length;b++){
      for(var c=b+1;c<pts.length;c++){
        var dd=Math.hypot(pts[b].x-pts[c].x, pts[b].y-pts[c].y);
        if(dd<LINK*DPR){
          ctx.beginPath(); ctx.moveTo(pts[b].x,pts[b].y); ctx.lineTo(pts[c].x,pts[c].y);
          ctx.strokeStyle='rgba(244,238,226,'+((1-dd/(LINK*DPR))*0.10).toFixed(3)+')';
          ctx.lineWidth=1*DPR; ctx.stroke();
        }
      }
    }
    // cursor tether to the nearest node
    if(hoverIdx>-1){
      ctx.beginPath(); ctx.moveTo(mouse.x,mouse.y);
      ctx.lineTo(pts[hoverIdx].x,pts[hoverIdx].y);
      ctx.strokeStyle='rgba(255,219,0,.42)'; ctx.lineWidth=1*DPR; ctx.stroke();
    }
    // nodes
    for(var d2=0;d2<pts.length;d2++){
      var q=pts[d2], on=(hoverIdx===d2), rad=q.r*(on?1.8:1);
      if(on||PLACES[d2].home){
        ctx.beginPath(); ctx.arc(q.x,q.y,rad*3.4,0,Math.PI*2);
        ctx.fillStyle='rgba(255,219,0,'+(on?.16:.09)+')'; ctx.fill();
      }
      ctx.beginPath(); ctx.arc(q.x,q.y,rad,0,Math.PI*2);
      ctx.fillStyle=(on||PLACES[d2].home)?'#ffdb00':'rgba(244,238,226,.78)'; ctx.fill();

      ctx.font=(on?'600 ':'')+(12*DPR)+'px Archivo, sans-serif';
      ctx.fillStyle=on?'#ffdb00':'rgba(244,238,226,.50)';
      ctx.fillText(PLACES[d2].n, q.x+rad+7*DPR, q.y+4*DPR);
    }

    if(visible&&!reduce) raf=requestAnimationFrame(frame);
  }

  var lastShown=-2;
  function paintReadout(){
    if(hoverIdx===lastShown) return;
    lastShown=hoverIdx;
    if(hoverIdx>-1){
      var p=PLACES[hoverIdx];
      readout.innerHTML='<span class="place">'+p.n+'</span><span class="who">'+p.w+'</span>';
    } else {
      readout.innerHTML='<span class="idle">Nine places, one studio. '+
        'Hover a point to see whose work is there.</span>';
    }
  }
  setInterval(paintReadout,90);

  function move(x,y){
    var r=canvas.getBoundingClientRect();
    mouse.x=(x-r.left)*DPR; mouse.y=(y-r.top)*DPR; mouse.on=true;
  }
  window.addEventListener('mousemove',function(e){move(e.clientX,e.clientY);},{passive:true});
  window.addEventListener('mouseout',function(){mouse.on=false;});
  window.addEventListener('touchmove',function(e){
    if(e.touches&&e.touches[0]) move(e.touches[0].clientX,e.touches[0].clientY);
  },{passive:true});
  window.addEventListener('touchend',function(){mouse.on=false;});
  window.addEventListener('resize',function(){resize(); if(reduce) frame(0);});
  document.addEventListener('visibilitychange',function(){
    visible=!document.hidden;
    if(visible&&!reduce&&!raf) raf=requestAnimationFrame(frame);
    if(!visible&&raf){cancelAnimationFrame(raf);raf=null;}
  });

  resize();
  if(reduce) frame(0); else raf=requestAnimationFrame(frame);
})();
