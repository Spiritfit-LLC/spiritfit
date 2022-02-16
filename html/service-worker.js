/**
 * Copyright 2016 Google Inc. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

// DO NOT EDIT THIS GENERATED OUTPUT DIRECTLY!
// This file should be overwritten as part of your build process.
// If you need to extend the behavior of the generated service worker, the best approach is to write
// additional code and include it using the importScripts option:
//   https://github.com/GoogleChrome/sw-precache#importscripts-arraystring
//
// Alternatively, it's possible to make changes to the underlying template file and then use that as the
// new base for generating output, via the templateFilePath option:
//   https://github.com/GoogleChrome/sw-precache#templatefilepath-string
//
// If you go that route, make sure that whenever you update your sw-precache dependency, you reconcile any
// changes made to this original template file with your modified copy.

// This generated service worker JavaScript will precache your site's resources.
// The code needs to be saved in a .js file at the top-level of your site, and registered
// from your pages in order to be used. See
// https://github.com/googlechrome/sw-precache/blob/master/demo/app/js/service-worker-registration.js
// for an example of how you can register this script and handle various service worker events.

/* eslint-env worker, serviceworker */
/* eslint-disable indent, no-unused-vars, no-multiple-empty-lines, max-nested-callbacks, space-before-function-paren, quotes, comma-spacing */
'use strict';

var precacheConfig = [["/action.html","965c9f23b45cbce35f2388dbccc2bdb2"],["/actions.html","3e2701d64372d3e7bbc2a38e2617b827"],["/animation.html","b47f8fc0b93f528bacee94a7281aec3a"],["/application.html","fe16f7db9de5e7627726e72dd285645c"],["/club.html","d05591ddc67bbde64274154e07014885"],["/clubs.html","af9e5176a991ebabf22808ed5237a76f"],["/css/style.min.css","c66894f6ae298f8ab5a1510dcd0ff41d"],["/faq.html","2a8a933d14675ce91c4f2c2d3448839f"],["/fonts/Gotham Pro/GothamPro-Bold.eot","ee9b8c537d217fef51d4bca8cf436d7d"],["/fonts/Gotham Pro/GothamPro-Bold.ttf","c15ee62b232cedc240947b6d814fb750"],["/fonts/Gotham Pro/GothamPro-Bold.woff","a3d7d652af07c3610c453b4a51c788c7"],["/fonts/Gotham Pro/GothamPro-Light.eot","7fd3861de823f75a6923000a948c494a"],["/fonts/Gotham Pro/GothamPro-Light.ttf","abd5115e2ddae490f9cb393fb8cdfc5a"],["/fonts/Gotham Pro/GothamPro-Light.woff","677c3a32938f905b22eb14afd1d5cff4"],["/fonts/Gotham Pro/GothamPro-Medium.eot","6fe466da15644ccc370d3b2ce3a19f6f"],["/fonts/Gotham Pro/GothamPro-Medium.ttf","c161369173f822acd66d2ff8eea64c52"],["/fonts/Gotham Pro/GothamPro-Medium.woff","b86e995a10856a8b8e222fe8ff00a74f"],["/fonts/Gotham Pro/GothamPro.eot","c31a8e097cb6c2901857d8c686f0063d"],["/fonts/Gotham Pro/GothamPro.ttf","3100f91bbd9e9ca9ecd00255cac7d11c"],["/fonts/Gotham Pro/GothamPro.woff","3cb46b372ab77d032576b9f70c83d1ff"],["/img/action-bgs/action-bg-1.jpg","7361ca5be68786758f9d4a384c4df00f"],["/img/action-bgs/action-bg-2.jpg","40ba4e036689542e1837e593b1e1993b"],["/img/advantage-slider.jpg","329e766153a4adf0cc9ae190211c3769"],["/img/advantage-slider.png","0a9955bc116efe1f8c21de7a352603d2"],["/img/appstore.png","433ac279d77de8aaaae46bceabdfddda"],["/img/background.jpg","d35fddf9f993852f80c9170fc5d3803f"],["/img/cloud-logo.png","b8c4802dd159c04c6c99e00150521707"],["/img/club-bgs/club-bg-1.png","b090f9ea8940ac5516e505d8e156e4b2"],["/img/club-bgs/club-bg-2.png","cee0a7c0231dab1da82c07ead10667c8"],["/img/club-bgs/club-bg-3.png","f9cc349a08dd82f04ac98c8d37fdffe8"],["/img/club-bgs/club-bg-4.png","26a431582642a66c5fd03faffbc6cad8"],["/img/contains-icons/contains-icon-1.png","9ff5bdf57214076969d82dca6f3be879"],["/img/contains-icons/contains-icon-2.png","e557003e8e42ca76c410b5cb39bcd538"],["/img/contains-icons/contains-icon-3.png","a93a645a323c96a27df475d477cca092"],["/img/contains-icons/contains-icon-4.png","c70e8152b7e1f89068127e57ee399377"],["/img/contains-icons/contains-icon-5.png","6c61d8eba098a121b376528339f4e899"],["/img/contains-icons/contains-icon-6.png","1c073d7f60100467dcb39b8759a357f4"],["/img/googleplay.png","89c388ec2437a6c8e31dd3266e435c26"],["/img/grid-element/grid-element-1.jpg","691a4fb9a504575c17847652c806329d"],["/img/grid-element/grid-element-2.jpg","dbd143d925185d69e89903a7431011b8"],["/img/grid-icons/grid-icon-1.png","63f2cf470af02df267590a0d9b8829c0"],["/img/grid-icons/grid-icon-2.png","437851387bc1ca046a306bdda4754119"],["/img/grid-icons/grid-icon-3.png","814710a591b9c5d9b04db5149c0aec8e"],["/img/grid-icons/grid-icon-4.png","bd6637d98cc98583d371ea37c28e50e7"],["/img/grid-icons/grid-icon-5.png","9a60d8f29f563c993fb2c66ce9bf70d3"],["/img/grid-icons/grid-icon-6.png","4df6ed77098e6fa2fd4a5024e02b858a"],["/img/gym-1.jpg","da787ac14b4f72016f737793485ce78c"],["/img/gym-2.jpg","7f0c136bf9948657ea64f00edb29849a"],["/img/icons/arrow-bottom.png","81e2b3cb2cff144fac7816af207f23ef"],["/img/icons/arrow-next-black.png","81689fa08ddcb7c2a81edc083d240378"],["/img/icons/arrow-next-orange.png","69c279469b3698b19c2a44a74fa79383"],["/img/icons/arrow-next.png","058141dd6bee2a46df8e2663a306ada3"],["/img/icons/arrow-prev-black.png","5233190a664f43b43b45d94a77f30e96"],["/img/icons/arrow-prev.png","16abc5980aa13abe9f066df7aa33c674"],["/img/icons/arrow-right-white.png","4ae7c15586489cc7ff40cb475eb9b674"],["/img/icons/arrow-right.png","af5d92cf91b9ccd3417565ef28c57536"],["/img/icons/caret.png","229ac814bf601469ee4ab01057523b70"],["/img/icons/list-check.png","0fafe20ce8fc626c8b655ed0dfeda0e8"],["/img/icons/pin.png","cc2b27ff44edd2fb25fccb938b77fdd6"],["/img/icons/plus.png","bc848c23f87e9ff303158f23198be61e"],["/img/icons/watch.png","9117b7897af5aca91baf1e6a27583de0"],["/img/inst-black.png","ab8a90f2348feb890c810d1b3ce91398"],["/img/inst.png","edd0b7a445efa5ae243eff4521e6e4a8"],["/img/logo-white.png","b3f99bcc439ae519b3d092d7d36f751b"],["/img/logo.png","bcee91f47764b9157bf7af6d76a931a6"],["/img/person.jpg","690b5e03ef6688bda67f72e030afaea5"],["/img/possibilities-imgs/possibilities-1.png","f3dfeede19699d9e7d7c76e7269e84fd"],["/img/possibilities-imgs/possibilities-2.png","cb404208d97ffa8dba62b83e8c76f1d3"],["/img/possibilities-imgs/possibilities-3.png","822d0d300b5256de54709e0454e42016"],["/img/possibilities-imgs/possibilities-4.png","54c3d64780f5e59ff3624caa8c1a699f"],["/img/possibilities-imgs/possibilities-5.png","74ec7755ca2d0e7c45ed6a65e348dc05"],["/img/possibilities-imgs/possibilities-6.png","1f995406495b97ee62b43cc7d8b5d6d8"],["/img/sub-bgs/sub-bg-1.jpg","f6b80fe949799eafa644192caeb053cb"],["/img/sub-bgs/sub-bg-2.jpg","af543b38b5877c8e3ba617efd713e611"],["/img/sub-bgs/sub-bg-3.jpg","932aa54e7c90e67987f850b826f35d0c"],["/img/team/team-1.jpg","4871e99957ff678d816f32a15ccbb2b5"],["/img/team/team-2.jpg","63cafea4dd24dd30c4983b91628a45ef"],["/img/video-bg-2.jpg","e1586a090e207bb5766f20be99aa6511"],["/img/video-bg.jpg","b86f8806cfa5c79cb341dee655ad5e76"],["/index.html","689fd9ab37d779dfd852e2aecba8bd73"],["/js/common.min.js","542ebc31a07f465ef3c039e7e864b89c"],["/js/libs.min.js","a444b27d88e5a8dea0d27e81ce99582e"],["/libs/perfect-scrollbar-1.4.0/docs/index.html","a870a83a9bbcf684381b48654955b94d"],["/libs/perfect-scrollbar-1.4.0/examples/always-visible.html","5710cfb39f57939b0b25251945a7266b"],["/libs/perfect-scrollbar-1.4.0/examples/basic.html","c3b72e0b15f5bd9b64a351536307d13f"],["/libs/perfect-scrollbar-1.4.0/examples/events.html","f8c4ecbe5b6de2122a94f7276450ac94"],["/libs/perfect-scrollbar-1.4.0/examples/infinite-scroll.html","013d5f3bf416a4f72d4f5913043f0514"],["/libs/perfect-scrollbar-1.4.0/examples/init-and-remove.html","623468601780dc55fa1ef5b9ec4a803b"],["/libs/perfect-scrollbar-1.4.0/examples/nested-native-scroll.html","e9083a88d2e765daa0e262bd5f276621"],["/libs/perfect-scrollbar-1.4.0/examples/options-handlers.html","cdc7eb33c9fd41efb31082457dc19b50"],["/libs/perfect-scrollbar-1.4.0/examples/options-minScrollbarLength.html","7c845591f73f0ac2d66a587c8c5cea97"],["/libs/perfect-scrollbar-1.4.0/examples/options-suppressScrollAxis.html","49e7771d800856102f82a64575bf178b"],["/libs/perfect-scrollbar-1.4.0/examples/options-useBothWheelAxes.html","c75c74f9d8dfa4dbad7ce0a1f2e90e39"],["/libs/perfect-scrollbar-1.4.0/examples/options-wheelPropagation.html","7d31a64f62a9cfcb7c9efbd928de5ca7"],["/libs/perfect-scrollbar-1.4.0/examples/options-wheelSpeed.html","15b9e5774f7c4d36b0e0af8bb7f63e57"],["/libs/perfect-scrollbar-1.4.0/examples/removable-list-element.html","456be775717aea41dc71df4505066f7f"],["/libs/perfect-scrollbar-1.4.0/examples/rtl.html","b252ba2f98ec6e7e47aa9ad0fe2dfd33"],["/libs/perfect-scrollbar-1.4.0/examples/scrollbars-with-margin.html","c3f41de379f9b786b25f38d5517e80ab"],["/libs/perfect-scrollbar-1.4.0/examples/show-on-initial-load.html","a431c5c3499daa2fdba1d46cacf31703"],["/libs/perfect-scrollbar-1.4.0/examples/table-content.html","1ff7597dcec1569dcfff02daaa24eef4"],["/libs/perfect-scrollbar-1.4.0/examples/text-content.html","433b21b40a3831ee7e617a88c0d038c1"],["/libs/perfect-scrollbar-1.4.0/examples/top-and-left-scrollbars.html","de15ed4ed35c6c2f3b8246b28cda58f9"],["/libs/select2-4.0.5/docs/announcements-4.0.html","08d77b2663825896f7a951773def741e"],["/libs/select2-4.0.5/docs/community.html","76cc4a106ccf604673a21a3d9a34785a"],["/libs/select2-4.0.5/docs/examples.html","406fa9373b070ecdf782f9581f7915ab"],["/libs/select2-4.0.5/docs/index.html","e5afcac40b68bee641d5a9237a1c2761"],["/libs/select2-4.0.5/docs/options-old.html","d04d73d0db2cf060afe4f62c0af6d099"],["/libs/select2-4.0.5/docs/options.html","d04d73d0db2cf060afe4f62c0af6d099"],["/libs/select2-4.0.5/tests/integration.html","7f06af3cca90b5c10b8f0a0a8e616056"],["/libs/select2-4.0.5/tests/unit.html","713e2b66c221175f1f07f59aa5558394"],["/main.html","132a5684a1f3370a8c5638430235249a"],["/product.html","a1c869e5ecbc6d9dc45e7c441466e036"],["/products.html","89eaaf8d76c837e93f2ac85193befbe2"],["/subscription.html","fc099babcb996fbb0c69562f784ef8f0"],["/subscriptions.html","cde58f544397eb2163dacf89e19afbd2"],["/timetable.html","82b6098dbb482471d7069651a7e70426"]];
var cacheName = 'sw-precache-v3--' + (self.registration ? self.registration.scope : '');


var ignoreUrlParametersMatching = [/^utm_/];



var addDirectoryIndex = function (originalUrl, index) {
    var url = new URL(originalUrl);
    if (url.pathname.slice(-1) === '/') {
      url.pathname += index;
    }
    return url.toString();
  };

var cleanResponse = function (originalResponse) {
    // If this is not a redirected response, then we don't have to do anything.
    if (!originalResponse.redirected) {
      return Promise.resolve(originalResponse);
    }

    // Firefox 50 and below doesn't support the Response.body stream, so we may
    // need to read the entire body to memory as a Blob.
    var bodyPromise = 'body' in originalResponse ?
      Promise.resolve(originalResponse.body) :
      originalResponse.blob();

    return bodyPromise.then(function(body) {
      // new Response() is happy when passed either a stream or a Blob.
      return new Response(body, {
        headers: originalResponse.headers,
        status: originalResponse.status,
        statusText: originalResponse.statusText
      });
    });
  };

var createCacheKey = function (originalUrl, paramName, paramValue,
                           dontCacheBustUrlsMatching) {
    // Create a new URL object to avoid modifying originalUrl.
    var url = new URL(originalUrl);

    // If dontCacheBustUrlsMatching is not set, or if we don't have a match,
    // then add in the extra cache-busting URL parameter.
    if (!dontCacheBustUrlsMatching ||
        !(url.pathname.match(dontCacheBustUrlsMatching))) {
      url.search += (url.search ? '&' : '') +
        encodeURIComponent(paramName) + '=' + encodeURIComponent(paramValue);
    }

    return url.toString();
  };

var isPathWhitelisted = function (whitelist, absoluteUrlString) {
    // If the whitelist is empty, then consider all URLs to be whitelisted.
    if (whitelist.length === 0) {
      return true;
    }

    // Otherwise compare each path regex to the path of the URL passed in.
    var path = (new URL(absoluteUrlString)).pathname;
    return whitelist.some(function(whitelistedPathRegex) {
      return path.match(whitelistedPathRegex);
    });
  };

var stripIgnoredUrlParameters = function (originalUrl,
    ignoreUrlParametersMatching) {
    var url = new URL(originalUrl);
    // Remove the hash; see https://github.com/GoogleChrome/sw-precache/issues/290
    url.hash = '';

    url.search = url.search.slice(1) // Exclude initial '?'
      .split('&') // Split into an array of 'key=value' strings
      .map(function(kv) {
        return kv.split('='); // Split each 'key=value' string into a [key, value] array
      })
      .filter(function(kv) {
        return ignoreUrlParametersMatching.every(function(ignoredRegex) {
          return !ignoredRegex.test(kv[0]); // Return true iff the key doesn't match any of the regexes.
        });
      })
      .map(function(kv) {
        return kv.join('='); // Join each [key, value] array into a 'key=value' string
      })
      .join('&'); // Join the array of 'key=value' strings into a string with '&' in between each

    return url.toString();
  };


var hashParamName = '_sw-precache';
var urlsToCacheKeys = new Map(
  precacheConfig.map(function(item) {
    var relativeUrl = item[0];
    var hash = item[1];
    var absoluteUrl = new URL(relativeUrl, self.location);
    var cacheKey = createCacheKey(absoluteUrl, hashParamName, hash, false);
    return [absoluteUrl.toString(), cacheKey];
  })
);

function setOfCachedUrls(cache) {
  return cache.keys().then(function(requests) {
    return requests.map(function(request) {
      return request.url;
    });
  }).then(function(urls) {
    return new Set(urls);
  });
}

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return setOfCachedUrls(cache).then(function(cachedUrls) {
        return Promise.all(
          Array.from(urlsToCacheKeys.values()).map(function(cacheKey) {
            // If we don't have a key matching url in the cache already, add it.
            if (!cachedUrls.has(cacheKey)) {
              var request = new Request(cacheKey, {credentials: 'same-origin'});
              return fetch(request).then(function(response) {
                // Bail out of installation unless we get back a 200 OK for
                // every request.
                if (!response.ok) {
                  throw new Error('Request for ' + cacheKey + ' returned a ' +
                    'response with status ' + response.status);
                }

                return cleanResponse(response).then(function(responseToCache) {
                  return cache.put(cacheKey, responseToCache);
                });
              });
            }
          })
        );
      });
    }).then(function() {
      
      // Force the SW to transition from installing -> active state
      return self.skipWaiting();
      
    })
  );
});

self.addEventListener('activate', function(event) {
  var setOfExpectedUrls = new Set(urlsToCacheKeys.values());

  event.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return cache.keys().then(function(existingRequests) {
        return Promise.all(
          existingRequests.map(function(existingRequest) {
            if (!setOfExpectedUrls.has(existingRequest.url)) {
              return cache.delete(existingRequest);
            }
          })
        );
      });
    }).then(function() {
      
      return self.clients.claim();
      
    })
  );
});


self.addEventListener('fetch', function(event) {
  if (event.request.method === 'GET') {
    // Should we call event.respondWith() inside this fetch event handler?
    // This needs to be determined synchronously, which will give other fetch
    // handlers a chance to handle the request if need be.
    var shouldRespond;

    // First, remove all the ignored parameters and hash fragment, and see if we
    // have that URL in our cache. If so, great! shouldRespond will be true.
    var url = stripIgnoredUrlParameters(event.request.url, ignoreUrlParametersMatching);
    shouldRespond = urlsToCacheKeys.has(url);

    // If shouldRespond is false, check again, this time with 'index.html'
    // (or whatever the directoryIndex option is set to) at the end.
    var directoryIndex = 'index.html';
    if (!shouldRespond && directoryIndex) {
      url = addDirectoryIndex(url, directoryIndex);
      shouldRespond = urlsToCacheKeys.has(url);
    }

    // If shouldRespond is still false, check to see if this is a navigation
    // request, and if so, whether the URL matches navigateFallbackWhitelist.
    var navigateFallback = '';
    if (!shouldRespond &&
        navigateFallback &&
        (event.request.mode === 'navigate') &&
        isPathWhitelisted([], event.request.url)) {
      url = new URL(navigateFallback, self.location).toString();
      shouldRespond = urlsToCacheKeys.has(url);
    }

    // If shouldRespond was set to true at any point, then call
    // event.respondWith(), using the appropriate cache key.
    if (shouldRespond) {
      event.respondWith(
        caches.open(cacheName).then(function(cache) {
          return cache.match(urlsToCacheKeys.get(url)).then(function(response) {
            if (response) {
              return response;
            }
            throw Error('The cached response that was expected is missing.');
          });
        }).catch(function(e) {
          // Fall back to just fetch()ing the request if some unexpected error
          // prevented the cached response from being valid.
          console.warn('Couldn\'t serve response for "%s" from cache: %O', event.request.url, e);
          return fetch(event.request);
        })
      );
    }
  }
});







