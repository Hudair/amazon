<script type="text/javascript">
  "use strict";;
  (function($, window, document) {
    $(document).ready(function() {
      let endTime = "{{ $endTime }}"

      const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

      let birthday = "Sep 30, 2021 00:00:00",
        countDown = new Date(endTime).getTime(),
        x = setInterval(function() {

          let now = new Date().getTime(),
            distance = countDown - now;

          document.getElementById("days").innerText = Math.floor(distance / (day)),
            document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
            document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
            document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

          //do something later when date is reached
          if (distance < 0) {
            let headline = document.getElementById("headline"),
              countdown = document.getElementById("countdown"),
              content = document.getElementById("content");

            headline.innerText = "Sorry sale over";
            countdown.style.display = "none";
            content.style.display = "block";

            clearInterval(x);
          }
          //seconds
        }, 0);

      // timer 2
      (function() {
        const second = 1000,
          minute = second * 60,
          hour = minute * 60,
          day = hour * 24;

        //let birthday = "jun 12, 2021 10:00:00",
        countDown = new Date(endTime).getTime(),
          x = setInterval(function() {

            let now = new Date().getTime(),
              distance = countDown - now;

            document.getElementById("days1").innerText = Math.floor(distance / (day)),
              document.getElementById("hours1").innerText = Math.floor((distance % (day)) / (hour)),
              document.getElementById("minutes1").innerText = Math.floor((distance % (hour)) / (minute)),
              document.getElementById("seconds1").innerText = Math.floor((distance % (minute)) / second);

            //do something later when date is reached
            if (distance < 0) {
              let headline1 = document.getElementById("headline1"),
                countdown1 = document.getElementById("countdown1"),
                content1 = document.getElementById("content1");

              headline1.innerText = "Sorry sale over";
              countdown1.style.display = "none";
              content1.style.display = "block";

              clearInterval(x);
            }
            //seconds
          }, 0)
      }());

      // timer 3
      (function() {
        const second = 1000,
          minute = second * 60,
          hour = minute * 60,
          day = hour * 24;

        let birthday = "mar 7, 2021 02:30:00",
          countDown = new Date(endTime).getTime(),
          x = setInterval(function() {

            let now = new Date().getTime(),
              distance = countDown - now;

            document.getElementById("days2").innerText = Math.floor(distance / (day)),
              document.getElementById("hours2").innerText = Math.floor((distance % (day)) / (hour)),
              document.getElementById("minutes2").innerText = Math.floor((distance % (hour)) / (minute)),
              document.getElementById("seconds2").innerText = Math.floor((distance % (minute)) / second);

            //do something later when date is reached
            if (distance < 0) {
              let headline2 = document.getElementById("headline2"),
                countdown2 = document.getElementById("countdown2"),
                content2 = document.getElementById("content2");

              headline2.innerText = "Sorry sale over";
              countdown2.style.display = "none";
              content2.style.display = "block";

              clearInterval(x);
            }
            //seconds
          }, 0)
      }());

      // $('.main-menu').mobileMegaMenu({
      //    changeToggleText: false,
      //    enableWidgetRegion: true,
      //    prependCloseButton: true,
      //    stayOnActive: true,
      //    // toogleTextOnClose: 'Close Menu',
      //    menuToggle: 'main-menu-toggle'
      // });
    });
  }(window.jQuery, window, document));
</script>
