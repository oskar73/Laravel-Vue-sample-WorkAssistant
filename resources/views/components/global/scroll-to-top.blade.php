<style>
    #scroll-to-top {
        font-size: 20px;
        text-align: center;
        color: #fff;
        text-decoration: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: none;
        border-radius: 50%;
        background: #000;
        border: 1px solid #2a2a2a;
        width: 35px;
        height: 35px;
        line-height: 30px;
        z-index: 999;
        outline: 0;
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        -ms-transition: all 0.3s ease;
    }
    #scroll-to-top i {
        color: #fff;
    }
    #scroll-to-top:hover {
        color: #282b2d;
        background: #fff;
    }
    #scroll-to-top:hover i {
        color: #282b2d;
    }
    #scroll-to-top:visited {
        color: #282b2d;
        text-decoration: none;
    }
</style>
<a href="#" id="scroll-to-top" >
    &#8593;
</a>
<script>
    const scrollTopElement = document.getElementById('scroll-to-top')
    if(scrollTopElement) {
      document.body.addEventListener('scroll', function () {
        if (window.scrollTop > 500) {
          $(scrollTopElement).show()
        } else {
          $(scrollTopElement).hide()
        }
      });

      scrollTopElement.addEventListener('click', function(event){
        event.preventDefault()
        window.scrollTo({
          top: 0,
          left: 0,
          behavior: 'smooth'
        })
      })
    } else {
      console.warn('scrollTopElement is null')
    }
</script>
