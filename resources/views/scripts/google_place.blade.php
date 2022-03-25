<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script>
  var autocompvare;
  var address1Field;
  var address2Field;
  var postalField;

  function initAutocomplete() {
    address1Field = document.querySelector("#address_line_1");
    address2Field = document.querySelector("#address_line_2");
    postalField = document.querySelector("#postcode");
    // Create the autocomplete object
    autocomplete = new google.maps.places.Autocomplete(address1Field, {
      // componentRestrictions: {
      // country: ["bd", "in"]
      // },
      // fields: ["address_components", "geometry"],
      fields: ["address_components"],
      types: ["address"],
    });

    // Wait for 500ms to load and then focus the address field
    setTimeout(() => {
      address1Field.focus();
    }, 500);

    // When the user selects an address from the drop-down, populate the
    // address fields in the form.
    autocomplete.addListener("place_changed", fillInAddress);
  }

  function fillInAddress() {
    // Get the place details from the autocomplete object.
    const place = autocomplete.getPlace();
    var address1 = "";
    var postcode = "";

    // Get each component of the address from the place details,
    // and then fill-in the corresponding field on the form.
    // place.address_components are google.maps.GeocoderAddressComponent objects
    // which are documented at http://goo.gle/3l5i5Mr
    for (const component of place.address_components) {
      const componentType = component.types[0];

      switch (componentType) {
        case "street_number": {
          address1 = `${component.long_name} ${address1}`;
          break;
        }

        case "route": {
          address1 += component.short_name;
          break;
        }

        case "postal_code": {
          postcode = `${component.long_name}${postcode}`;
          break;
        }

        case "postal_code_suffix": {
          postcode = `${postcode}-${component.long_name}`;
          break;
        }
        case "locality":
          document.querySelector("#address_city").value = component.long_name;
          break;

        case "administrative_area_level_1": {
          document.querySelector("#address_state").value = component.short_name;
          break;
        }
        case "country":
          document.querySelector("#address_country").value = component.long_name;
          break;
      }
    }
    address1Field.value = address1;
    postalField.value = postcode;

    // After filling the form set cursor focus on the second address line
    address2Field.focus();
  }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.place_api_key') }}&callback=initAutocomplete&libraries=places&v=weekly" async></script>

<style>
  .pac-container {
    z-index: 100000;
  }

</style>
