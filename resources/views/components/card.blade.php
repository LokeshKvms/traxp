@php
  if (isset($type) && $type === 'cash-in') {
    $bg = $bgf = 'bg-teal-50';
    $clr = $clrf = 'text-green-600';
    $em = '💰 ';
  } elseif (isset($type) && $type === 'cash-out') {
    $bg = $bgf = 'bg-red-50';
    $clr = $clrf = 'text-red-600';
    $em = '💸 ';
  } else {
    $bgf = 'bg-white';
    $clrf = 'text-black';
    $em = '📊 ';

    if ($back < 0) {
      $bg = 'bg-red-50';
      $clr = 'text-red-600';
    } elseif ($back > 0) {
      $bg = 'bg-teal-50';
      $clr = 'text-green-600';
    } else {
      $bg = 'bg-black';
      $clr = 'text-white';
    }
  }
@endphp

<style>
	.card {
		width: 300px;
		height: 200px;
		perspective: 1000px;
		font-weight: 600;
	}

	.card-inner {
		width: 100%;
		height: 100%;
		position: relative;
		transform-style: preserve-3d;
		transition: transform 0.999s;
		box-shadow: #191c24 0px 0px 10px 0px;
		border-radius: 10px;
	}

	.card:hover .card-inner {
		transform: rotateY(180deg);
	}

	.card-front,
	.card-back {
		position: absolute;
		width: 100%;
		height: 100%;
		backface-visibility: hidden;
	}

	.card-front {
		display: flex;
		align-items: center;
		border-radius: 10px;
		justify-content: center;
		font-size: 24px;
		transform: rotateY(0deg);
	}

	.card-back {
		display: flex;
		align-items: center;
		border-radius: 10px;
		justify-content: center;
		font-size: 24px;
		transform: rotateY(180deg);
	}

</style>
<div class="card">
  <div class="card-inner">
    <div class="card-front {{ $bgf }} {{ $clrf }}">
      <p>{{ $em }}{{ $front ?? 'Front Side' }}</p>
    </div>
    <div class="card-back {{ $bg }} {{ $clr }}">
      <a href={{ $link ?? '#' }} class="flex flex-col items-center justify-center h-full w-full">
        <p> ₹{{ $back ?? 'Back Side' }}</p>
      </a>
    
    </div>
  </div>
</div>
