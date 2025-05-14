{{-- Credit: Lucide (https://lucide.dev) --}}

@props([
    'variant' => 'outline',
])

@php
if ($variant === 'solid') {
    throw new \Exception('The "solid" variant is not supported in Lucide.');
}

$classes = Flux::classes('shrink-0')
    ->add(match($variant) {
        'outline' => '[:where(&)]:size-6',
        'solid' => '[:where(&)]:size-6',
        'mini' => '[:where(&)]:size-5',
        'micro' => '[:where(&)]:size-4',
    });

$strokeWidth = match ($variant) {
    'outline' => 2,
    'mini' => 2.25,
    'micro' => 2.5,
};
@endphp

<svg
    {{ $attributes->class($classes) }}
    data-flux-icon
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $strokeWidth }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
    data-slot="icon"
>
  <path d="M14 2v4a2 2 0 0 0 2 2h4" />
  <path d="m2.305 15.53.923-.382" />
  <path d="m3.228 12.852-.924-.383" />
  <path d="M4.677 21.5a2 2 0 0 0 1.313.5H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v2.5" />
  <path d="m4.852 11.228-.383-.923" />
  <path d="m4.852 16.772-.383.924" />
  <path d="m7.148 11.228.383-.923" />
  <path d="m7.53 17.696-.382-.924" />
  <path d="m8.772 12.852.923-.383" />
  <path d="m8.772 15.148.923.383" />
  <circle cx="6" cy="14" r="3" />
</svg>
