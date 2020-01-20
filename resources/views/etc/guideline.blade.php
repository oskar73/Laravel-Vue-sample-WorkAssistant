@php($defaultFont = 'Montserrat Regular')
@php($offset=189)
<svg id="svg" width="100%" height="{{ $offset * count($colorNames) }}px" fill="none" style="width: 100%; padding: 45px;"
     xmlns="http://www.w3.org/2000/svg">
    <text fill="#333333" xml:space="preserve" style="white-space: pre" font-family="{{ $defaultFont }}" font-size="20"
          font-weight="600" letter-spacing="0em">
        <tspan x="0" y="19.17">Fonts</tspan>
    </text>
    <text fill="#333333" xml:space="preserve" style="white-space: pre" font-family="{{ $defaultFont }}" font-size="20"
          font-weight="600" letter-spacing="0em">
        <tspan x="449" y="19.17">Recommended Palette</tspan>
    </text>
    @php($colorBlockY=59)
    @php($colorBlockCircleY=144)
    @php($colorTextNameY=78.17)
    @php($colorTextValueY=111.17)

    @foreach($colorNames as $color => $colorName)
        <rect x="449" y="{{ $colorBlockY }}" width="90" height="115" rx="3" fill="{{ $color }}"/>
        <rect opacity="0.8" x="552" y="{{ $colorBlockCircleY }}" width="30" height="30" rx="15" fill="{{ $color }}"/>
        <rect opacity="0.6" x="597" y="{{ $colorBlockCircleY }}" width="30" height="30" rx="15" fill="{{ $color }}"/>
        <rect opacity="0.4" x="642" y="{{ $colorBlockCircleY }}" width="30" height="30" rx="15" fill="{{ $color }}"/>
        <rect opacity="0.2" x="687" y="{{ $colorBlockCircleY }}" width="30" height="30" rx="15" fill="{{ $color }}"/>
        <text fill="black" xml:space="preserve" style="white-space: pre" font-family="{{ $defaultFont }}" font-size="20"
              letter-spacing="0em">
            <tspan x="567" y="{{ $colorTextNameY }}">{{ $colorName }}</tspan>
        </text>
        <text fill="#CCCCCC" xml:space="preserve" style="white-space: pre" font-family="{{ $defaultFont }}"
              font-size="20" letter-spacing="0em">
            <tspan x="567" y="{{ $colorTextValueY }}">{{ $color }}</tspan>
        </text>
        @php($colorBlockY=$colorBlockY+$offset)
        @php($colorBlockCircleY=$colorBlockCircleY+$offset)
        @php($colorTextNameY=$colorTextNameY+$offset)
        @php($colorTextValueY=$colorTextValueY+$offset)
    @endforeach
    @php($fontNameY=99.4688)
    @php($textPartOneY=134.238)
    @php($textPartTwoY=154.238)
    @php($circleForTextY=110)
    @php($offset=141)
    @foreach($fontNames as $fontName)
        <text fill="#818181" xml:space="preserve" style="white-space: pre" font-family="{{ $fontName }}" font-size="18"
              font-weight="500"
              letter-spacing="0em"><tspan x="27" y="{{ $textPartOneY }}">The quick brown fox</tspan>
            <tspan x="27" y="{{ $textPartTwoY }}">jumps over the lazydog.</tspan>
        </text>
        <text fill="#333333" xml:space="preserve" style="white-space: pre" font-family="{{ $defaultFont }}"
              font-size="34"
              font-weight="500"
              letter-spacing="0em">
            <tspan x="27" y="{{ $fontNameY }}">{{ $fontName }}</tspan>
        </text>
        <circle cx="4" cy="{{ $circleForTextY }}" r="4" fill="#333333"/>
        @php($fontNameY+=$offset)
        @php($textPartOneY+=$offset)
        @php($textPartTwoY+=$offset)
        @php($circleForTextY+=$offset)
    @endforeach
    <line y1="28.5" x2="95" y2="28.5" stroke="#E7E7E7"/>
    <line x1="449" y1="28.5" x2="720" y2="28.5" stroke="#E7E7E7"/>
</svg>