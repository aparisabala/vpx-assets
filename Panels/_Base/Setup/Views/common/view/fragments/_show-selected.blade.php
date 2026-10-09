@php
	$top  = -28;
	if(isset($n_top)) {
		$top = $n_top;
	}
@endphp
<div style="position: absolute;margin-top: {{ $top }}px;margin-left: -500px;" id="show_selected_base">
    <span style="background-color: green;padding: 3px;color: white;">
    	<span id="show_selected"></span>
    </span>
</div>
