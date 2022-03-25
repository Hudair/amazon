<script type="text/javascript">
  let endTime = "{{ isset($flashdeals) ? $flashdeals['end_time'] : 'NaN' }}";

  if (endTime) {
    const second = 1000,
      minute = second * 60,
      hour = minute * 60,
      day = hour * 24;

    // Parse the endTime element
    var t = endTime.split(/[- :]/);
    endTime = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));

    let countDown = new Date(endTime).getTime(),
      x = setInterval(function() {
        let now = new Date().getTime(),
          distance = countDown - now;

        $('.deal-counter-days').text(Math.floor(distance / (day)));
        $('.deal-counter-hours').text(Math.floor((distance % (day)) / (hour)));
        $('.deal-counter-minutes').text(Math.floor((distance % (hour)) / (minute)));
        $('.deal-counter-seconds').text(Math.floor((distance % (minute)) / second));

        //do something later when date is reached
        if (distance < 0) {
          let headline = document.getElementById("headline"),
            countdown = document.getElementById("countdown"),
            content = document.getElementById("content");

          headline.innerText = "{{ trans('theme.sale_over') }}";
          countdown.style.display = "none";
          content.style.display = "block";

          clearInterval(x);
        }
        //seconds
      }, 0);
  }
</script>
