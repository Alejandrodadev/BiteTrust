
<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-primaryLight mb-4">Política de Cookies</h1>

        <p class="text-gray-600 mb-4 text-sm">
            En BiteTrust utilizamos cookies propias y de terceros para mejorar tu experiencia en nuestra web, ofrecerte contenidos personalizados y analizar el tráfico.
        </p>

        <h2 class="text-xl font-semibold text-primaryLight mt-6 mb-2">¿Qué son las cookies?</h2>
        <p class="text-gray-600 mb-4 text-sm">
            Las cookies son archivos pequeños que se descargan en tu dispositivo cuando accedes a ciertos servicios. Permiten, entre otras cosas, almacenar y recuperar información sobre tus hábitos de navegación.
        </p>

        <h2 class="text-xl font-semibold text-primaryLight mt-6 mb-2">Tipos de cookies que utilizamos</h2>
        <ul class="list-disc list-inside text-gray-600 mb-4 text-sm">
            <li><strong>Cookies técnicas:</strong> Esenciales para la navegación y el funcionamiento de la web.</li>
            <li><strong>Cookies de preferencia:</strong> Recuerdan tus elecciones (idioma, ajustes de visualización).</li>
            <li><strong>Cookies analíticas:</strong> Nos ayudan a entender cómo interactúas con el sitio y mejorar tu experiencia.</li>
            <li><strong>Cookies publicitarias:</strong> Permiten ofrecerte publicidad relevante según tus intereses.</li>
        </ul>

        <h2 class="text-xl font-semibold text-primaryLight mt-6 mb-2">Cómo gestionar y deshabilitar cookies</h2>
        <p class="text-gray-600 mb-4 text-sm">
            Puedes permitir, bloquear o eliminar las cookies instaladas en tu dispositivo modificando la configuración de tu navegador:
        </p>
        <ul class="list-disc list-inside text-gray-600 mb-4 text-sm">
            <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" class="text-primaryLight hover:underline">Google Chrome</a></li>
            <li><a href="https://support.mozilla.org/kb/habilitar-y-deshabilitar-cookies-sitios-web-r" target="_blank" class="text-primaryLight hover:underline">Mozilla Firefox</a></li>
            <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" class="text-primaryLight hover:underline">Safari</a></li>
            <li><a href="https://support.microsoft.com/help/17442/windows-internet-explorer-delete-manage-cookies" target="_blank" class="text-primaryLight hover:underline">Internet Explorer</a></li>
        </ul>

        <h2 class="text-xl font-semibold text-primaryLight mt-6 mb-2">Consentimiento y cambios en la política</h2>
        <p class="text-gray-600 mb-4 text-sm">
            Al navegar por nuestro sitio aceptas el uso de cookies según esta política. Podemos actualizar esta política en cualquier momento; te recomendamos revisarla periódicamente.
        </p>

        <div class="mt-4">
            <a href="{{ url()->previous() }}"
               class="inline-block px-4 py-2 bg-white border-gray-400 text-form rounded hover:bg-primaryLight transition text-sm">
                Volver
            </a>
        </div>
    </div>
</x-app-layout>
