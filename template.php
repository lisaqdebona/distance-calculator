<div id="distance">
  <div class="distanceInner">
  	<p class="h2">How far from the Whitehouse am I?</p>
    <div id="form-msg"></div>
    <form id="distanceform" action="" method="get" autocomplete="off">
      <input type="hidden" name="userLat" id="userLat">
      <input type="hidden" name="userLong" id="userLong">
      <input type="hidden" name="lat2" id="lat2" value="<?php echo (isset($lat2)) ? $lat2:'' ?>">
      <input type="hidden" name="long2" id="long2" value="<?php echo (isset($long2)) ? $long2:'' ?>">

      <label class="input-field">
        <span class="form-label">Type your address or click `Use Current Location`:</span>
        <div class="inputfield">
          <div class="inputdiv">
            <input id="userloc" type="text" name="userloc" required autocomplete="off" />
          </div>
          <div class="btndiv">
            <a href="#" id="useCurrentLocBtn" class="disabled" data-lat="" data-long=""><span id="btnTxt">Use Current Location</span></a>
          </div>
        </div>
      </label>

      <label class="input-field dest2">
        <span class="form-label">Destination:</span>
        <div class="inputfield">
          <div class="inputdiv">
            <input id="dest2" type="text" name="dest2" disabled value="<?php echo (isset($destinationAddress)) ? $destinationAddress:'' ?>"/>
            <input type="hidden" name="destination2" value="<?php echo (isset($destinationAddress)) ? $destinationAddress:'' ?>">
          </div>
        </div>
      </label>
      <a href="#" id="checkBtn"><span>Calculate Distance</span></a>
    </form>
  	
    <div class="mapOuterWrap">
      <div id="map"></div>
      <div id="msg"></div>
    </div>
  </div>
</div>