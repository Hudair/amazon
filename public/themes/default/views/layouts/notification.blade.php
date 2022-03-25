$(document).ready(function(){
$.notify({
// oprions
icon: 'fas fa-{{ $icon ?? 'paw' }}',
title: "<strong>{{ trans('theme.' . $type) }}:</strong> ",
message: '{{ $message ?? '' }}'
},{
// settings
type: '{{ $type ?? 'info' }}',
delay: 1500,
placement: {
from: "top",
align: "right"
},
});
});
