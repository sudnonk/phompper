(()=>{"use strict";var n,e,o=google.maps.LatLng,t=google.maps.Map,l=google.maps.Marker,a=google.maps.Geocoder,i=null;function r(n,o){var t;null!==i&&i.setMap(null),i=new l({position:n,map:o}),t=n,e.geocode({location:t}).then((function(n){var e,o=null===(e=null==n?void 0:n.results[0])||void 0===e?void 0:e.formatted_address;if(null===o)throw new Error("取得結果に住所が含まれていません");u(o.replace(/^日本([、,])/,""))})).catch((function(n){console.log(n),u("住所の取得に失敗しました")}))}function c(n){console.log(n);var e=document.getElementById("map_status");null!==e&&(e.innerText=n)}function u(n){var e=document.getElementById("address");null!==e&&(e.innerText=n)}window.initMap=function(){var l=new o({lat:35.681217751538604,lng:139.76709999359113}),i=document.getElementById("map");if(null!==i){n=new t(i,{center:l,zoom:17});var u=function(n){c("現在位置を取得しています・・・");var e=n,t=navigator.geolocation,l=function(n){e=new o({lat:n.coords.latitude,lng:n.coords.longitude})},a=function(){c("現在位置の取得に失敗しました。東京を表示します。")},i={enableHighAccuracy:!0,timeout:5e3,maximumAge:0};return t.getCurrentPosition(l,a,i),e}(l);n.panTo(u),r(u,n),n.addListener("click",(function(e){!function(e){var t=e.latLng;if(null===t)return;var l=new o(t);r(l,n),n.panTo(l)}(e)})),n.addListener("center_changed",(function(){console.log("center_changed")})),e=new a,console.log("Google Mapsを読み込みました。")}else c("エラーが発生しました。管理者に連絡してください。")},window.gm_authFailure=function(){c("Google Mapsを読み込めませんでした。")}})();