@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
    'class' => '
        border-gray-300 dark:border-gray-700
        bg-form dark:bg-gray-900
        text-white dark:text-gray-300
        focus:border-primary dark:focus:border-primary
        focus:ring-primary dark:focus:ring-primary
        rounded-md shadow-sm
    '
]) }}>
