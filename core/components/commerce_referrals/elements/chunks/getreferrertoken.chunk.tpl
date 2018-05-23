<script>
    var url_string = window.location.href;
    var url = new URL(url_string);
    var referrer = url.searchParams.get("ref");
    if(referrer) {
        document.getElementById('referrer').value = referrer;
    }
    //console.log(document.getElementById('referrer').value);
</script>