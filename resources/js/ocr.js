document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('[data-ocr-form]');

    if (!form) {
        return;
    }

    const input = form.querySelector('input[type="file"]');
    const result = document.querySelector('[data-ocr-result]');

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (!input.files.length) {
            if (result) {
                result.innerHTML = '<p style="color: #fef2f2;">Selecciona una imagen primero.</p>';
            }
            return;
        }

        const formData = new FormData();
        formData.append('image', input.files[0]);

        if (result) {
            result.innerHTML = '<p>Cargando sugerencias...</p>';
        }

        try {
            const response = await fetch('/scanner/analyze', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const data = await response.json();

            if (result) {
                result.innerHTML = `
                    <div style="display: flex; flex-direction: column; gap: 0.45rem; padding: 0.9rem; border-radius: 0.8rem; background: rgba(255,255,255,0.08);">
                        <p style="margin: 0;"><strong>Nombre sugerido:</strong> ${data.suggested_name || 'Sin sugerencia'}</p>
                        <p style="margin: 0;"><strong>Voltaje sugerido:</strong> ${data.suggested_voltage || 'N/D'} V</p>
                        <p style="margin: 0;"><strong>Potencia sugerida:</strong> ${data.suggested_power || 'N/D'} W</p>
                        <p style="margin: 0;"><strong>Horas/día sugeridas:</strong> ${data.suggested_hours_per_day || 'N/D'}</p>
                    </div>
                `;

                const formTarget = document.querySelector('[data-device-form]');
                if (formTarget) {
                    const nameInput = formTarget.querySelector('input[name="name"]');
                    const powerInput = formTarget.querySelector('input[name="power"]');
                    const hoursInput = formTarget.querySelector('input[name="hours_per_day"]');

                    if (nameInput) {
                        nameInput.value = data.suggested_name || '';
                    }
                    if (powerInput) {
                        powerInput.value = data.suggested_power || '';
                    }
                    if (hoursInput) {
                        hoursInput.value = data.suggested_hours_per_day || '';
                    }
                }
            }
        } catch (error) {
            if (result) {
                result.innerHTML = '<p style="color: #fef2f2;">No se pudo analizar la imagen.</p>';
            }
        }
    });
});
