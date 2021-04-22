interact('.digital-signature')
  .draggable({
    inertia: true,
    restrict: {
      restriction: "parent",
      endOnly: true,
      elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
    },
    autoScroll: true,
    onmove: function (event) {
      var target = event.target,
          x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
          y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

      target.style.webkitTransform = target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
      target.style.border = '2px dashed #ddd';
      target.classList.remove('digital-signature--remove')

      target.setAttribute('data-x', x);
      target.setAttribute('data-y', y);
      console.log('Coordinate X,Y(' + event.pageX + ', ' + event.pageY + ')')
    },
    onend: function (event) {
      $("#llx").val(event.pageX);
      $("#lly").val(event.pageY);
      var la = event.pageX-150;
      var llx_result = la-365;
      var lb = event.pageY + 36.68;
      var llb = lb-330;
      var lly_result = 841-llb;
      var urx = llx_result+150;
      var ury = lly_result+36.68;
      $("#llx_result").val(llx_result);
      $("#lly_result").val(lly_result); 
      $("#urx").val(urx);
      $("#ury").val(ury);

      var target = event.target;
      target.classList.add('digital-signature--remove')
    }
  });
