<!-- service -->
<template id="service">
    <div class="form-check form-switch">
        <input
            id=""
            class="form-check-input"
            type="checkbox"
            name="service_ids[]"
            value=""
        >
        <label
            class="form-check-label"
            for=""
            style="user-select: none; cursor: pointer;"
        ></label>
    </div>
</template>

<script>
    const renderServices = (services) => {
        services.forEach((service) => {
            const serviceElement = serviceTemplate.cloneNode(true);
            serviceElement.querySelector('input').id = `service-${service.id}`;
            serviceElement.querySelector('label').setAttribute('for', `service-${service.id}`);

            if (service.user_id) {
                serviceElement.querySelector('input').checked = true;
            }

            serviceElement.querySelector('input').value = service.id;
            serviceElement.querySelector('label').textContent = service.name;

            serviceContainer.append(serviceElement);
        });
    };
</script>
