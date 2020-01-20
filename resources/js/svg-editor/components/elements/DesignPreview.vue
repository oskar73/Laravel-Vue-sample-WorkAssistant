<template>
  <!-- Slider main container -->
  <div class="swiper-container">
    <!-- Additional required wrapper -->
    <div v-show="isMounted" class="swiper-wrapper">
      <!-- Slides -->
      <div class="swiper-slide">
        <div class="preview">
          <svg width="100%" height="100%" viewBox="0 0 559 760" fill="none" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink">
            <g filter="url(#card_filter0_d)">
              <rect x="3" y="6" width="536" height="340" fill="white" />
            </g>
            <g filter="url(#card_filter1_d)">
              <rect x="3" y="400" width="536" height="340" fill="white" />
            </g>
            <rect x="0.5" y="1.5" width="545" height="350" fill="url(#card_pattern0)" stroke="#E3E3E3" />
            <rect x="0.5" y="394.5" width="545" height="350" fill="url(#card_pattern1)" stroke="#E3E3E3" />
            <rect x="41" y="0" width="462" height="260" fill="url(#card_pattern2)" />
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="21"
              font-weight="500" letter-spacing="0.4em">
              <tspan x="32" y="443.328">JOHNATAN</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="21"
              font-weight="500" letter-spacing="0.4em">
              <tspan x="31" y="465.328">ROBINSON</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.4em">
              <tspan x="32.1906" y="484.616">DIRECTOR</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.1em">
              <tspan x="32.0766" y="666.616">00 123 456 789</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.1em">
              <tspan x="305.634" y="666.616">LEDBURY CONSTRUCTION LTD</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.1em">
              <tspan x="305.152" y="683.616">112 WESTBOURN STUDIOS</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.1em">
              <tspan x="305.289" y="700.616">242 AKHLAM ROAD</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.1em">
              <tspan x="304.763" y="717.616">LONDON W10 5JJ</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.1em">
              <tspan x="32" y="683.616">WWW.COMPANYNAME.COM</tspan>
            </text>
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.1em">
              <tspan x="32" y="700.616">WWW.JOHNROB.COM</tspan>
            </text>
            <line x1="243" y1="265.5" x2="303" y2="265.5" :stroke="getLogoColor()" stroke-width="3" />
            <line x1="32" y1="500.5" x2="92" y2="500.5" :stroke="getLogoColor()" stroke-width="3" />
            <text fill="#272F3F" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="12"
              letter-spacing="0.1em">
              <tspan x="171.604" y="306.722">WWW.COMPANYNAME.COM</tspan>
            </text>
            <defs>
              <filter id="card_filter0_d" x="3" y="6" width="556" height="360" filterUnits="userSpaceOnUse"
                color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
                <feOffset dx="10" dy="10" />
                <feGaussianBlur stdDeviation="5" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
              </filter>
              <filter id="card_filter1_d" x="3" y="400" width="556" height="360" filterUnits="userSpaceOnUse"
                color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
                <feOffset dx="10" dy="10" />
                <feGaussianBlur stdDeviation="5" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
              </filter>
              <pattern id="card_pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#card_image0"
                  transform="translate(0 1.61698) scale(0.000694444 0.00108025) rotate(-90)" />
              </pattern>
              <pattern id="card_pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#card_image0"
                  transform="translate(0 1.61698) scale(0.000694444 0.00108025) rotate(-90)" />
              </pattern>
              <pattern id="card_pattern2" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#card_image1" transform="translate(0 0.0553279) scale(0.00204082 0.00362998)" />
              </pattern>
              <image id="card_image0" width="2068" height="1440"
                :xlink:href="asset('assets/img/preview/visit-card/bg.png')" />
              <image v-if="previewUpdated" id="card_image1" width="462" height="260" :xlink:href="previewUrl" />
            </defs>
          </svg>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="preview">
          <svg width="100%" height="100%" viewBox="0 0 1007 1060" fill="none" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink">
            <rect x="48.4771" y="26.4424" width="910.295" height="1037.12" fill="url(#file_pattern0)" />
            <rect x="100" y="200" width="400" height="225" fill="url(#file_pattern1)" fill-opacity="0.8" />
            <rect x="705" y="520" width="250" height="138" fill="url(#file_pattern2)" fill-opacity="0.9" />
            <line x1="766" y1="650" x2="790" y2="650" :stroke="getLogoColor()" stroke-opacity="0.4" stroke-width="2" />
            <defs>
              <pattern id="file_pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#file_image0" transform="translate(-0.0205229) scale(0.00107992 0.000947867)" />
              </pattern>
              <pattern id="file_pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#file_image1" transform="translate(-0.084812) scale(0.00238699 0.00408163)" />
              </pattern>
              <pattern id="file_pattern2" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#file_image1" transform="translate(-0.081857) scale(0.00237493 0.00408163)" />
              </pattern>
              <image id="file_image0" width="964" height="1055" :xlink:href="asset('assets/img/preview/blank/bg.png')" />
              <image id="file_image1" width="490" height="245" :xlink:href="previewUrl" />
            </defs>
          </svg>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="preview">
          <svg width="100%" height="100%" viewBox="0 0 737 735" fill="none" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink">
            <rect width="737" height="858.053" fill="url(#envelope_pattern0)" />
            <line x1="621.881" y1="386.488" x2="644.43" y2="386.488" :stroke="getLogoColor()" stroke-width="2" />
            <path d="M249.227 10.6816L639.089 11.275L645.011 87.2288L238.546 87.8233L249.227 10.6816Z" fill="#C1689D"
              fill-opacity="0.25" />
            <rect x="40" y="345" width="400" height="222.2" fill="url(#envelope_pattern1)" fill-opacity="0.8" />
            <line x1="420.125" y1="672.507" x2="442.675" y2="672.507" :stroke="getLogoColor()" stroke-width="2" />
            <defs>
              <pattern id="envelope_pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#envelope_image0" transform="translate(0 -0.00483129) scale(0.00107296 0.000921589)" />
              </pattern>
              <pattern id="envelope_pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#envelope_image1" transform="translate(-0.0639811) scale(0.00230196 0.00408163)" />
              </pattern>
              <image id="envelope_image0" width="932" height="971"
                :xlink:href="asset('assets/img/preview/envelope/bg.png')" />
              <image v-if="previewUpdated" id="envelope_image1" width="490" height="245" :href="previewUrl" />
            </defs>
          </svg>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="preview">
          <svg width="100%" height="100%" viewBox="0 0 594 362" fill="none" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink" style="max-width: 900px">
            <rect width="594" height="362" fill="url(#macbook_pattern0)" />

            <rect x="195" y="10" width="200" height="111" fill="url(#macbook_pattern1)" fill-opacity="0.8" />
            <text fill="#929292" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
              letter-spacing="0em">
              <tspan x="70.1309" y="62.28">HOME</tspan>
            </text>
            <text fill="#080808" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
              letter-spacing="0em">
              <tspan x="136.19" y="62.28">SHOP</tspan>
            </text>
            <text fill="#080808" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
              letter-spacing="0em">
              <tspan x="367.018" y="62.28">CART</tspan>
            </text>
            <text fill="#929292" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="10"
              letter-spacing="0em">
              <tspan x="392.564" y="62.28">($2)</tspan>
            </text>
            <text fill="#929292" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="10"
              letter-spacing="0em">
              <tspan x="444.398" y="62.118">(0)</tspan>
            </text>
            <text fill="#080808" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
              letter-spacing="0em">
              <tspan x="194.33" y="62.28">PAGES</tspan>
            </text>
            <text fill="#080808" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
              letter-spacing="0em">
              <tspan x="494.394" y="62.28">LOGIN</tspan>
            </text>
            <rect x="431" y="54" width="11" height="10" fill="url(#macbook_pattern2)" />
            <rect width="14" height="14" transform="matrix(-1 0 0 1 492 51)" fill="url(#macbook_pattern3)" />
            <text fill="#4E4E4E" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
              font-weight="500" letter-spacing="0em">
              <tspan x="70" y="114.28">FEATURED ITEMS</tspan>
            </text>
            <g filter="url(#macbook_filter0_d)">
              <rect x="70" y="129" width="129.013" height="134" fill="white" />
              <text fill="#2E2E2E" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
                font-weight="300" letter-spacing="0em">
                <tspan x="74" y="239.28">Pin Button Badge</tspan>
              </text>
              <text fill="#808080" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="8"
                font-weight="300" letter-spacing="0em">
                <tspan x="182.522" y="255.694">12$</tspan>
              </text>
              <text fill="#808080" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="8"
                font-weight="300" letter-spacing="0em">
                <tspan x="74" y="253.424">Cosmetic</tspan>
              </text>
            </g>
            <g filter="url(#macbook_filter1_d)">
              <rect x="231.993" y="129" width="129.013" height="134" fill="white" />
              <text fill="#2E2E2E" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
                font-weight="300" letter-spacing="0em">
                <tspan x="236" y="239.28">Soap Package</tspan>
              </text>
              <text fill="#808080" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="8"
                font-weight="300" letter-spacing="0em">
                <tspan x="236" y="253.424">Hygiene</tspan>
              </text>
              <text fill="#808080" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="8"
                font-weight="300" letter-spacing="0em">
                <tspan x="345" y="255.694">16$</tspan>
              </text>
            </g>
            <g filter="url(#macbook_filter2_d)">
              <rect x="393.987" y="129" width="129.013" height="134" fill="white" />
              <text fill="#2E2E2E" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="10"
                font-weight="300" letter-spacing="0em">
                <tspan x="398" y="239.28">Milk Glass Bottle</tspan>
              </text>
              <text fill="#808080" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="8"
                font-weight="300" letter-spacing="0em">
                <tspan x="398" y="253.424">Foodstuff</tspan>
              </text>
              <text fill="#808080" xml:space="preserve" style="white-space: pre" font-family="Montserrat" font-size="8"
                font-weight="300" letter-spacing="0em">
                <tspan x="510.39" y="255.694">2$</tspan>
              </text>
            </g>
            <rect width="9" height="9" transform="matrix(-1 0 0 1 523 107)" fill="url(#macbook_pattern4)" />
            <rect width="9" height="9" transform="matrix(-1 0 0 1 508 107)" fill="url(#macbook_pattern5)" />
            <rect x="70" y="292" width="453" height="30" fill="#D9D9D9" />
            <text fill="#2E2E2E" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="8"
              font-weight="300" letter-spacing="0em">
              <tspan x="89" y="308.8">&#xa9; 2019. All rights reserved.</tspan>
            </text>
            <path
              d="M469 303.065C468.595 303.246 468.161 303.368 467.704 303.423C468.17 303.142 468.527 302.696 468.696 302.166C468.259 302.426 467.776 302.616 467.262 302.718C466.851 302.276 466.265 302 465.615 302C464.369 302 463.359 303.018 463.359 304.272C463.359 304.45 463.379 304.624 463.417 304.79C461.542 304.695 459.879 303.79 458.766 302.415C458.572 302.751 458.461 303.141 458.461 303.558C458.461 304.346 458.86 305.042 459.465 305.449C459.095 305.437 458.747 305.334 458.443 305.164V305.192C458.443 306.293 459.221 307.212 460.253 307.421C460.063 307.472 459.864 307.501 459.658 307.501C459.512 307.501 459.371 307.486 459.233 307.459C459.52 308.362 460.354 309.019 461.341 309.037C460.569 309.646 459.595 310.009 458.538 310.009C458.356 310.009 458.177 309.997 458 309.977C458.999 310.623 460.184 311 461.459 311C465.61 311 467.879 307.537 467.879 304.533L467.871 304.239C468.315 303.921 468.698 303.52 469 303.065Z"
              :fill="getLogoColor()" />
            <path
              d="M484.597 311V306.895H485.775L485.952 305.295H484.597V304.273C484.597 303.81 484.706 303.494 485.275 303.494L486 303.494V302.063C485.875 302.044 485.444 302 484.944 302C483.898 302 483.182 302.746 483.182 304.115V305.295H482V306.895H483.182V311H484.597Z"
              :fill="getLogoColor()" />
            <path
              d="M500.318 311H506.682C507.409 311 508 310.409 508 309.682V303.318C508 302.591 507.409 302 506.682 302H500.318C499.591 302 499 302.591 499 303.318V309.682C499 310.409 499.591 311 500.318 311ZM499.527 303.318C499.527 302.882 499.882 302.527 500.318 302.527H506.682C507.118 302.527 507.473 302.882 507.473 303.318V309.682C507.473 310.118 507.118 310.473 506.682 310.473H500.318C499.882 310.473 499.527 310.118 499.527 309.682V303.318Z"
              :fill="getLogoColor()" />
            <path
              d="M503.5 308.873C504.808 308.873 505.873 307.808 505.873 306.5C505.873 305.192 504.808 304.127 503.5 304.127C502.192 304.127 501.127 305.192 501.127 306.5C501.127 307.808 502.192 308.873 503.5 308.873ZM503.5 304.654C504.518 304.654 505.346 305.482 505.346 306.5C505.346 307.518 504.518 308.346 503.5 308.346C502.482 308.346 501.654 307.518 501.654 306.5C501.654 305.482 502.482 304.654 503.5 304.654Z"
              :fill="getLogoColor()" />
            <path
              d="M506.137 304.654C506.573 304.654 506.928 304.299 506.928 303.863C506.928 303.427 506.573 303.072 506.137 303.072C505.701 303.072 505.346 303.427 505.346 303.863C505.346 304.299 505.701 304.654 506.137 304.654ZM506.137 303.6C506.282 303.6 506.4 303.718 506.4 303.863C506.4 304.009 506.282 304.127 506.137 304.127C505.991 304.127 505.873 304.009 505.873 303.863C505.873 303.718 505.991 303.6 506.137 303.6Z"
              :fill="getLogoColor()" />
            <rect x="70" y="129" width="129" height="96" fill="#D9D9D9" />
            <rect x="232" y="129" width="129" height="96" fill="#D9D9D9" />
            <rect x="394" y="129" width="129" height="96" fill="#D9D9D9" />
            <path d="M44 15.3C44 8.5069 49.5069 3 56.3 3H536.7C543.493 3 549 8.5069 549 15.3V22H44V15.3Z"
              fill="#F1F1F1" />
            <g filter="url(#macbook_filter3_i)">
              <circle cx="57.5" cy="12.5" r="3.5" fill="#DA4F00" />
            </g>
            <g filter="url(#macbook_filter4_i)">
              <circle cx="69.5" cy="12.5" r="3.5" fill="#F79B12" />
            </g>
            <g filter="url(#macbook_filter5_i)">
              <circle cx="81.5" cy="12.5" r="3.5" fill="#2BCB71" />
            </g>
            <line x1="522" y1="8.625" x2="534" y2="8.625" stroke="#9E9E9E" stroke-width="0.75" />
            <line x1="522" y1="12.625" x2="534" y2="12.625" stroke="#9E9E9E" stroke-width="0.75" />
            <line x1="522" y1="16.625" x2="534" y2="16.625" stroke="#9E9E9E" stroke-width="0.75" />
            <rect x="141" y="7" width="309" height="11" rx="2" fill="white" />
            <text fill="#CECECE" xml:space="preserve" style="white-space: pre" font-family="Catamaran" font-size="8"
              letter-spacing="0em">
              <tspan x="265.766" y="14.74">www.company.com</tspan>
            </text>
            <defs>
              <pattern id="macbook_pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#macbook_image0" transform="translate(-0.000488477) scale(0.000394707 0.000647668)" />
              </pattern>
              <pattern id="macbook_pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#macbook_image1" transform="translate(-0.06) scale(0.00228571 0.00408163)" />
              </pattern>
              <pattern id="macbook_pattern2" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#macbook_image2" transform="translate(0 0.00612245) scale(0.00102041 0.00112245)" />
              </pattern>
              <pattern id="macbook_pattern3" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#macbook_image3" transform="scale(0.0277778)" />
              </pattern>
              <filter id="macbook_filter0_d" x="68.1" y="127.1" width="133.013" height="138" filterUnits="userSpaceOnUse"
                color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
                <feOffset dx="0.1" dy="0.1" />
                <feGaussianBlur stdDeviation="1" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
              </filter>
              <filter id="macbook_filter1_d" x="230.093" y="127.1" width="133.013" height="138"
                filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
                <feOffset dx="0.1" dy="0.1" />
                <feGaussianBlur stdDeviation="1" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
              </filter>
              <filter id="macbook_filter2_d" x="392.087" y="127.1" width="133.013" height="138"
                filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
                <feOffset dx="0.1" dy="0.1" />
                <feGaussianBlur stdDeviation="1" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
              </filter>
              <pattern id="macbook_pattern4" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#macbook_image4" transform="scale(0.00195312)" />
              </pattern>
              <pattern id="macbook_pattern5" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#macbook_image4" transform="translate(1 1) scale(0.00195313) rotate(180)" />
              </pattern>
              <filter id="macbook_filter3_i" x="54" y="9" width="7.1" height="7.1" filterUnits="userSpaceOnUse"
                color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                  result="hardAlpha" />
                <feOffset dx="0.1" dy="0.1" />
                <feGaussianBlur stdDeviation="0.5" />
                <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="shape" result="effect1_innerShadow" />
              </filter>
              <filter id="macbook_filter4_i" x="66" y="9" width="7.1" height="7.1" filterUnits="userSpaceOnUse"
                color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                  result="hardAlpha" />
                <feOffset dx="0.1" dy="0.1" />
                <feGaussianBlur stdDeviation="0.5" />
                <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="shape" result="effect1_innerShadow" />
              </filter>
              <filter id="macbook_filter5_i" x="78" y="9" width="7.1" height="7.1" filterUnits="userSpaceOnUse"
                color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                  result="hardAlpha" />
                <feOffset dx="0.1" dy="0.1" />
                <feGaussianBlur stdDeviation="0.5" />
                <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="shape" result="effect1_innerShadow" />
              </filter>
              <image id="macbook_image0" width="2536" height="1544"
                :xlink:href="asset('assets/img/preview/macbook/macbook.png')" />
              <image v-if="previewUpdated" id="macbook_image1" width="490" height="245" :href="previewUrl" />
              <image id="macbook_image2" width="980" height="880"
                :xlink:href="asset('assets/img/preview/macbook/like.png')" />
              <image id="macbook_image3" width="36" height="36"
                :xlink:href="asset('assets/img/preview/macbook/user.png')" />
              <image id="macbook_image4" width="512" height="512"
                :xlink:href="asset('assets/img/preview/macbook/next-icon.png')" />
            </defs>
          </svg>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="preview slide-edit-button">
          <div class="slide-edit-button__wrapper">
            <img :src="previewUrl" class="slide-edit-button__image" />
            <transition name="bounceUp">
              <a v-show="slides.edit.button.isVisible" class="animated-button animate-button" title="choose logo"
                itemprop="url" hreflang="en" @click.prevent="clickEditBtn">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
                Edit Design
                <i class="fa fa-chevron-right"></i>
              </a>
            </transition>
          </div>
        </div>
      </div>
    </div>
    <!-- If we need navigation buttons -->
    <div v-show="previewUrl" class="swiper-button-prev"></div>
    <div v-show="previewUrl" class="swiper-button-next"></div>
  </div>
</template>

<script>
import Swiper from 'swiper'
import Vibrant from 'node-vibrant'
import appMixin from '../../mixins/app-mixin'
import eventBus from '@/public/eventBus'

export default {
  name: 'DesignPreview',
  mixins: [appMixin],
  data() {
    return {
      swiper: {},
      swiperConfig: {
        speed: 500
      },
      slides: {
        edit: {
          index: 4,
          button: {
            isVisible: false
          }
        }
      },
      previewUrl: null,
      previewUpdated: false,
      colors: [],
      isMounted: false,
      editorUrl: null,
      modalView: false
    }
  },
  mounted() {
    this.initSwiper().then(() => {
      this.listen()
      this.isMounted = true
    })
  },

  beforeDestroy() {
    eventBus.$off('design.preview.set')
  },

  methods: {
    initSwiper() {
      return new Promise((resolve) => {
        // Set swiper instance
        this.swiper = new Swiper('.swiper-container', this.getSwiperOptions())

        return resolve()
      })
    },
    clickEditBtn() {
      if (this.modalView) {
        this.isOpenPreview = false
      } else {
        window.location.href = this.editorUrl
      }
    },
    listen() {
      eventBus.$on('design.preview.set', (data) => {
        this.setPreviewUrl(data.preview)
        this.setLogoColors(data.preview)
        this.modalView = data.modal
      })

      // eslint-disable-next-line
      eventBus.$on('logotype.set.edit-url', (editorUrl) => {
        this.editorUrl = editorUrl
      })

      this.swiper.on('slideChange', () => {
        if (this.swiper.activeIndex === this.slides.edit.index) {
          // eslint-disable-next-line
          eventBus.$emit('footer.button-next.hide')

          this.$nextTick(() => {
            setTimeout(() => {
              this.slides.edit.button.isVisible = true
            }, this.swiperConfig.speed)
          })
        } else {
          // Show button in footer and hide in slide
          // eslint-disable-next-line
          eventBus.$emit('footer.button-next.show')

          this.$nextTick(() => {
            setTimeout(() => {
              this.slides.edit.button.isVisible = false
            }, this.swiperConfig.speed)
          })
        }
      })
    },

    setPreviewUrl(previewUrl) {
      this.previewUpdated = false

      setTimeout(() => {
        this.previewUpdated = true
        this.previewUrl = previewUrl

        this.$nextTick(() => {
          this.swiper.update()
        })
      }, 1000)
    },

    setLogoColors(previewUrl) {
      Vibrant.from(previewUrl)
        .getSwatches()
        .then((swatches) => {
          // Clear old colors
          this.colors = []

          // Set new colors
          for (const swatch in swatches) {
            if (swatches.hasOwnProperty(swatch) && swatches[swatch]) {
              this.colors.push(swatches[swatch].getHex())
            }
          }
        })
    },

    getLogoColor() {
      return this.colors[0] || '#959595'
    },

    getSwiperOptions() {
      const mainConfig = {
        pagination: {
          el: '.swiper-pagination'
        },
        slidesPerView: 1,
        centeredSlides: true,
        grabCursor: true
        // navigation: {
        //   nextEl: '.swiper-button-next',
        //   prevEl: '.swiper-button-prev'
        // }
      }

      const animationConfig = {
        effect: 'cube',
        speed: this.swiperConfig.speed,
        cubeEffect: {
          slideShadows: true,
          shadow: true,
          shadowOffset: 100,
          shadowScale: 0.6
        }
      }

      if (this.isIE()) {
        return mainConfig
      }
      console.log('d ->', Object.assign({}, animationConfig, mainConfig))
      return Object.assign({}, animationConfig, mainConfig)
    },

    isEditSlideActive() {
      return this.swiper.activeIndex === this.slides.edit.index
    }
  }
}
</script>

<style lang="scss" scoped>
@import 'swiper/css';
@import 'swiper/css/pagination';
@import 'swiper/css/navigation';

.swiper-container {
  width: 100%;

  @media (min-width: 768px) {
    height: calc(100vh - 140px);
  }

  .slide-edit-button {
    display: flex;
    flex-direction: column;
    padding-top: 10%;

    .slide-edit-button__wrapper {
      min-width: 400px;

      .animated-button {
        text-transform: none;
        letter-spacing: 0;
        align-items: center;
        margin: 20px auto auto auto;
        display: flex;
        width: fit-content;
      }

      .slide-edit-button__image {
        display: block;
        margin: auto;
        width: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
      }
    }
  }
}

.swiper-slide {
  display: flex;
}

.preview {
  padding: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  background-position: center;
  background-repeat: no-repeat;
  background-size: contain;

  @media (min-width: 768px) {
    height: 100%;
    overflow: hidden;
    overflow-y: auto;
  }
}

.buttons-wrapper {
  position: absolute;
  display: flex;
  justify-content: space-between;
  right: 51%;
  top: 45px;
  width: 96px;
  height: auto;
  margin-right: -48px;
}

@media only screen and (max-width: 489px) {
  .logo-to-mockup2 {
    width: 180px;
    position: absolute;
    top: 340px;
    margin-right: -90px;
  }
}
</style>
