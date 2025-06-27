{{-- @extends('backend.layouts.app')

@section('content')

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Edit Shipping Company') }}</h5>
        </div>

        <form action="{{ route('admin.shipping-companies.update', $shippingCompany->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Método para actualizar -->
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">
                        {{ translate('Name') }} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" value="{{ old('name', $shippingCompany->name) }}" placeholder="Name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="api_endpoint">
                        {{ translate('API Endpoint') }}
                    </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="api_endpoint" value="{{ old('api_endpoint', $shippingCompany->api_endpoint) }}" placeholder="API Endpoint">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="whatsapp_number">
                        {{ translate('WhatsApp Number') }}
                    </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="whatsapp_number" value="{{ old('whatsapp_number', $shippingCompany->whatsapp_number) }}" placeholder="WhatsApp Number">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="default_message">
                        {{ translate('Default Message') }}
                    </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="default_message" placeholder="Default Message">{{ old('default_message', $shippingCompany->default_message) }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="latitude">
                        {{ translate('Latitude') }}
                    </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="latitude" value="{{ old('latitude', $shippingCompany->latitude) }}" placeholder="Latitude">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="longitude">
                        {{ translate('Longitude') }}
                    </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="longitude" value="{{ old('longitude', $shippingCompany->longitude) }}" placeholder="Longitude">
                    </div>
                </div>

                <div class="form-group mb-3 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection --}}




@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Edit Shipping Company') }}</h5>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.shipping-companies.update', $company->id) }}" method="POST" id="shipping-form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">
                            {{ translate('Name') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="{{ old('name', $company->name) }}"
                                placeholder="Name" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="api_endpoint">
                            {{ translate('API Endpoint') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="api_endpoint" value="{{ old('api_endpoint', $company->api_endpoint) }}"
                                placeholder="API Endpoint" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="whatsapp_number">
                            {{ translate('WhatsApp Number') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="whatsapp_number"
                                value="{{ old('whatsapp_number', $company->whatsapp_number) }}" placeholder="WhatsApp Number" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="default_message">
                            {{ translate('Default Message') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="default_message" placeholder="Default Message" required>{{ old('default_message', $company->default_message) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="latitude">
                            {{ translate('Latitude') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="latitude" value="{{ old('latitude', $company->latitude) }}"
                                placeholder="Latitude" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="longitude">
                            {{ translate('Longitude') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="longitude" value="{{ old('longitude', $company->longitude) }}"
                                placeholder="Longitude" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="address">
                            {{ translate('Address') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="address" value="{{ old('address', $company->address) }}" placeholder="Address" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="city">
                            {{ translate('City') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="city" value="{{ old('city', $company->city) }}" placeholder="City" required>
                        </div>
                    </div>
                    <!-- Sección de tablas editables -->
                    <div class="mt-4">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="cities-tab" data-toggle="tab" href="#cities-panel"
                                    role="tab">Ciudades</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="zones-tab" data-toggle="tab" href="#zones-panel"
                                    role="tab">Zonas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="towns-tab" data-toggle="tab" href="#towns-panel"
                                    role="tab">Pueblos</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <!-- Pestaña de Ciudades -->
                            <div class="tab-pane fade show active" id="cities-panel" role="tabpanel">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5>Lista de Ciudades</h5>
                                    <button type="button" class="btn btn-sm btn-success" id="add-city-row">
                                        <i class="fas fa-plus"></i> Añadir Ciudad
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="cities-table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Precio</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas agregadas dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pestaña de Zonas -->
                            <div class="tab-pane fade" id="zones-panel" role="tabpanel">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5>Lista de Zonas</h5>
                                    <button type="button" class="btn btn-sm btn-success" id="add-zone-row">
                                        <i class="fas fa-plus"></i> Añadir Zona
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="zones-table">
                                        <thead>
                                            <tr>
                                                <th>Ciudad</th>
                                                <th>Nombre</th>
                                                <th>Latitud</th>
                                                <th>Longitud</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas agregadas dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pestaña de Pueblos -->
                            <div class="tab-pane fade" id="towns-panel" role="tabpanel">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5>Lista de Pueblos</h5>
                                    <button type="button" class="btn btn-sm btn-success" id="add-town-row">
                                        <i class="fas fa-plus"></i> Añadir Pueblo
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="towns-table">
                                        <thead>
                                            <tr>
                                                <th>Ciudad</th>
                                                <th>Zona</th>
                                                <th>Nombre</th>
                                                <th>Latitud</th>
                                                <th>Longitud</th>
                                                <th>Días</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas agregadas dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3 text-right mt-4">
                        <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Variables de control
        let cityCounter = 0;
        let zoneCounter = 0;
        let townCounter = 0;
        let cities = [];
        let zones = [];
        let savedCityIds = {}; // Para mapear IDs de ciudades entre front y back
        let savedZoneIds = {}; // Para mapear IDs de zonas entre front y back

        // Datos iniciales de la compañía
        const initialData = @json($company->load('cities.zones.towns'));

        // Función para agregar una fila de ciudad
        function addCityRow(cityData = null) {
            const tbody = document.querySelector('#cities-table tbody');
            
            const cityName = cityData ? cityData.nombre : '';
            const cityPrice = cityData ? cityData.precio : '';
            const dbCityId = cityData ? cityData.id : null;
            
            const newRow = `
                <tr id="city-row-${cityCounter}" class="city-row" ${dbCityId ? 'data-db-id="'+dbCityId+'"' : ''}>
                    <td>
                        <input type="text" class="form-control city-name" name="cities[${cityCounter}][nombre]" value="${cityName}" required>
                        ${dbCityId ? '<input type="hidden" name="cities['+cityCounter+'][id]" value="'+dbCityId+'">' : ''}
                    </td>
                    <td>
                        <input type="number" class="form-control" name="cities[${cityCounter}][precio]" value="${cityPrice}" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row" data-target="city-row-${cityCounter}">
                            <i class="las la-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', newRow);

            // Guardar mapeo de ID si existe
            if (dbCityId) {
                savedCityIds[cityCounter] = dbCityId;
            }

            // Agregar a la lista de ciudades
            cities.push({
                id: cityCounter,
                name: cityName,
                dbId: dbCityId
            });

            // Actualizar selectores en otras pestañas
            updateCitySelectors();

            cityCounter++;
            return cityCounter - 1; // Devuelve el ID usado
        }

        // Función para agregar una fila de zona
        function addZoneRow(zoneData = null, cityIdMapping = null) {
            if (cities.length === 0) {
                alert('Debes agregar al menos una ciudad primero');
                document.getElementById('cities-tab').click();
                return null;
            }

            const tbody = document.querySelector('#zones-table tbody');
            
            const zoneName = zoneData ? zoneData.nombre : '';
            const zoneLatitude = zoneData ? zoneData.latitud : '';
            const zoneLongitude = zoneData ? zoneData.longitud : '';
            const dbZoneId = zoneData ? zoneData.id : null;
            
            // Obtener el ID de la ciudad en el frontend
            let selectedCityId = cities[0].id;
            if (zoneData && zoneData.shipping_company_city_id && cityIdMapping) {
                // Buscar el ID de la ciudad en el frontend que corresponde al ID de la BD
                for (const [frontId, dbId] of Object.entries(cityIdMapping)) {
                    if (parseInt(dbId) === parseInt(zoneData.shipping_company_city_id)) {
                        selectedCityId = parseInt(frontId);
                        break;
                    }
                }
            }

            const cityOptions = cities.map(city => {
                const cityName = document.querySelector(`#city-row-${city.id} .city-name`).value || `Ciudad ${city.id}`;
                const selected = city.id === selectedCityId ? 'selected' : '';
                return `<option value="${city.id}" ${selected}>${cityName}</option>`;
            }).join('');

            const newRow = `
                <tr id="zone-row-${zoneCounter}" class="zone-row" ${dbZoneId ? 'data-db-id="'+dbZoneId+'"' : ''}>
                    <td>
                        <select class="form-control zone-city" name="zones[${zoneCounter}][city_id]" required>
                            ${cityOptions}
                        </select>
                        ${dbZoneId ? '<input type="hidden" name="zones['+zoneCounter+'][id]" value="'+dbZoneId+'">' : ''}
                    </td>
                    <td>
                        <input type="text" class="form-control zone-name" name="zones[${zoneCounter}][nombre]" value="${zoneName}" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="zones[${zoneCounter}][latitud]" value="${zoneLatitude}" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="zones[${zoneCounter}][longitud]" value="${zoneLongitude}" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row" data-target="zone-row-${zoneCounter}">
                            <i class="las la-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', newRow);

            // Guardar mapeo de ID si existe
            if (dbZoneId) {
                savedZoneIds[zoneCounter] = dbZoneId;
            }

            // Agregar a la lista de zonas
            zones.push({
                id: zoneCounter,
                city_id: selectedCityId,
                name: zoneName,
                dbId: dbZoneId
            });

            // Actualizar selectores en otras pestañas
            updateZoneSelectors();

            zoneCounter++;
            return zoneCounter - 1; // Devuelve el ID usado
        }

        // Función para agregar una fila de pueblo
        function addTownRow(townData = null, zoneIdMapping = null) {
            if (zones.length === 0) {
                alert('Debes agregar al menos una zona primero');
                document.getElementById('zones-tab').click();
                return;
            }

            const tbody = document.querySelector('#towns-table tbody');
            
            const townName = townData ? townData.nombre : '';
            const townLatitude = townData ? townData.latitud : '';
            const townLongitude = townData ? townData.longitud : '';
            const dbTownId = townData ? townData.id : null;
            
            // Obtener el ID de la zona en el frontend
            let selectedZoneId = zones[0].id;
            let selectedCityId = zones[0].city_id;
            
            if (townData && townData.shipping_company_zone_id && zoneIdMapping) {
                // Buscar el ID de la zona en el frontend que corresponde al ID de la BD
                for (const [frontId, dbId] of Object.entries(zoneIdMapping)) {
                    if (parseInt(dbId) === parseInt(townData.shipping_company_zone_id)) {
                        selectedZoneId = parseInt(frontId);
                        // Buscar la ciudad asociada a esta zona
                        const zoneObj = zones.find(z => z.id === selectedZoneId);
                        if (zoneObj) {
                            selectedCityId = zoneObj.city_id;
                        }
                        break;
                    }
                }
            }

            // Obtener días disponibles
            let diasDisponibles = [];
            if (townData && townData.dias_disponibles) {
                try {
                    if (typeof townData.dias_disponibles === 'string') {
                        diasDisponibles = JSON.parse(townData.dias_disponibles);
                    } else {
                        diasDisponibles = townData.dias_disponibles;
                    }
                } catch(e) {
                    console.error('Error al parsear dias_disponibles:', e);
                }
            }

            const cityOptions = cities.map(city => {
                const cityName = document.querySelector(`#city-row-${city.id} .city-name`).value || `Ciudad ${city.id}`;
                const selected = city.id === selectedCityId ? 'selected' : '';
                return `<option value="${city.id}" ${selected}>${cityName}</option>`;
            }).join('');

            // Filtrar zonas por la ciudad seleccionada
            const cityZones = zones.filter(zone => parseInt(zone.city_id) === parseInt(selectedCityId));
            const zoneOptions = cityZones.map(zone => {
                const zoneName = document.querySelector(`#zone-row-${zone.id} .zone-name`).value || `Zona ${zone.id}`;
                const selected = zone.id === selectedZoneId ? 'selected' : '';
                return `<option value="${zone.id}" data-city="${zone.city_id}" ${selected}>${zoneName}</option>`;
            }).join('');

            // Crear options para los días con selecciones marcadas
            const diasOptions = [
                { value: "Lunes", label: "Lunes" },
                { value: "Martes", label: "Martes" },
                { value: "Miercoles", label: "Miercoles" },
                { value: "Jueves", label: "Jueves" },
                { value: "Viernes", label: "Viernes" },
                { value: "Sábado", label: "Sábado" },
                { value: "Domingo", label: "Domingo" }
            ].map(dia => {
                const selected = diasDisponibles.includes(dia.value) ? 'selected' : '';
                return `<option value="${dia.value}" ${selected}>${dia.label}</option>`;
            }).join('');

            const newRow = `
                <tr id="town-row-${townCounter}" class="town-row" ${dbTownId ? 'data-db-id="'+dbTownId+'"' : ''}>
                    <td>
                        <select class="form-control town-city" name="towns[${townCounter}][city_id]" required onchange="updateTownZones(this, ${townCounter})">
                            ${cityOptions}
                        </select>
                        ${dbTownId ? '<input type="hidden" name="towns['+townCounter+'][id]" value="'+dbTownId+'">' : ''}
                    </td>
                    <td>
                        <select class="form-control town-zone" name="towns[${townCounter}][zone_id]" required>
                            ${zoneOptions}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="towns[${townCounter}][nombre]" value="${townName}" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="towns[${townCounter}][latitud]" value="${townLatitude}" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="towns[${townCounter}][longitud]" value="${townLongitude}" required>
                    </td>
                    <td>
                        <select class="form-control" name="towns[${townCounter}][dias_disponibles][]" multiple required>
                            ${diasOptions}
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row" data-target="town-row-${townCounter}">
                            <i class="las la-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', newRow);

            townCounter++;
        }

        // Inicializar con datos existentes
        function initializeWithExistingData() {
            // Si no hay datos, agregar una fila vacía y salir
            if (!initialData || !initialData.cities || initialData.cities.length === 0) {
                addCityRow();
                return;
            }

            // Cargar ciudades
            initialData.cities.forEach(cityData => {
                const cityFrontId = addCityRow(cityData);
                
                // Cargar zonas para esta ciudad
                if (cityData.zones && cityData.zones.length > 0) {
                    cityData.zones.forEach(zoneData => {
                        const zoneFrontId = addZoneRow(zoneData, savedCityIds);
                        
                        // Cargar pueblos para esta zona
                        if (zoneData.towns && zoneData.towns.length > 0) {
                            zoneData.towns.forEach(townData => {
                                addTownRow(townData, savedZoneIds);
                            });
                        }
                    });
                }
            });
        }

        // Actualizar selectores de ciudades
        function updateCitySelectors() {
            // Actualizar nombres de ciudades en arrays
            document.querySelectorAll('.city-row').forEach((row) => {
                const cityId = parseInt(row.id.split('-')[2]);
                const cityName = row.querySelector('.city-name').value || `Ciudad ${cityId}`;

                // Actualizar en el array
                const cityIndex = cities.findIndex(c => c.id === cityId);
                if (cityIndex !== -1) {
                    cities[cityIndex].name = cityName;
                }

                // Actualizar en selectores
                document.querySelectorAll('.zone-city, .town-city').forEach(select => {
                    const option = select.querySelector(`option[value="${cityId}"]`);
                    if (option) {
                        option.textContent = cityName;
                    } else {
                        const newOption = document.createElement('option');
                        newOption.value = cityId;
                        newOption.textContent = cityName;
                        select.appendChild(newOption);
                    }
                });
            });
        }

        // Actualizar selectores de zonas
        function updateZoneSelectors() {
            // Actualizar nombres de zonas en arrays
            document.querySelectorAll('.zone-row').forEach((row) => {
                const zoneId = parseInt(row.id.split('-')[2]);
                const zoneName = row.querySelector('.zone-name').value || `Zona ${zoneId}`;
                const cityId = row.querySelector('.zone-city').value;

                // Actualizar en el array
                const zoneIndex = zones.findIndex(z => z.id === zoneId);
                if (zoneIndex !== -1) {
                    zones[zoneIndex].name = zoneName;
                    zones[zoneIndex].city_id = cityId;
                }

                // Actualizar en selectores
                document.querySelectorAll('.town-zone').forEach(select => {
                    const option = select.querySelector(`option[value="${zoneId}"]`);
                    if (option) {
                        option.textContent = zoneName;
                        option.setAttribute('data-city', cityId);
                    } else {
                        const newOption = document.createElement('option');
                        newOption.value = zoneId;
                        newOption.textContent = zoneName;
                        newOption.setAttribute('data-city', cityId);
                        select.appendChild(newOption);
                    }
                });
            });
        }

        // Actualizar zonas cuando se cambia la ciudad en un pueblo
        function updateTownZones(citySelect, townId) {
            const selectedCityId = citySelect.value;
            const zoneSelect = document.querySelector(`#town-row-${townId} .town-zone`);

            // Filtrar zonas por ciudad
            const cityZones = zones.filter(zone => parseInt(zone.city_id) === parseInt(selectedCityId));

            // Actualizar opciones
            zoneSelect.innerHTML = '';
            cityZones.forEach(zone => {
                const zoneName = document.querySelector(`#zone-row-${zone.id} .zone-name`).value ||
                    `Zona ${zone.id}`;
                const option = document.createElement('option');
                option.value = zone.id;
                option.textContent = zoneName;
                option.setAttribute('data-city', zone.city_id);
                zoneSelect.appendChild(option);
            });
        }

        // Remover filas
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row') || e.target.parentElement.classList.contains(
                    'remove-row')) {
                const button = e.target.classList.contains('remove-row') ? e.target : e.target.parentElement;
                const targetId = button.getAttribute('data-target');
                const row = document.getElementById(targetId);

                if (row) {
                    // Identificar tipo de fila
                    if (targetId.startsWith('city-row-')) {
                        const cityId = parseInt(targetId.split('-')[2]);

                        // Verificar si hay zonas asociadas
                        const associatedZones = zones.filter(zone => parseInt(zone.city_id) === cityId);

                        if (associatedZones.length > 0) {
                            if (!confirm(
                                    `Esta ciudad tiene ${associatedZones.length} zona(s) asociada(s). Si eliminas la ciudad, también se eliminarán sus zonas y pueblos. ¿Deseas continuar?`
                                )) {
                                return;
                            }

                            // Eliminar zonas asociadas
                            associatedZones.forEach(zone => {
                                // Eliminar pueblos asociados a esta zona
                                document.querySelectorAll(`.town-row .town-zone option[value="${zone.id}"]`)
                                    .forEach(option => {
                                        const townRow = option.closest('.town-row');
                                        if (townRow && townRow.querySelector('.town-zone').value == zone
                                            .id) {
                                            townRow.remove();
                                        }
                                    });

                                // Eliminar la fila de zona
                                const zoneRow = document.getElementById(`zone-row-${zone.id}`);
                                if (zoneRow) zoneRow.remove();

                                // Eliminar del array
                                const zoneIndex = zones.findIndex(z => z.id === zone.id);
                                if (zoneIndex !== -1) zones.splice(zoneIndex, 1);
                            });
                        }

                        // Eliminar la ciudad del array
                        const cityIndex = cities.findIndex(c => c.id === cityId);
                        if (cityIndex !== -1) cities.splice(cityIndex, 1);

                    } else if (targetId.startsWith('zone-row-')) {
                        const zoneId = parseInt(targetId.split('-')[2]);

                        // Verificar si hay pueblos asociados
                        const hasTowns = document.querySelector(`.town-zone[value="${zoneId}"]`) !== null;

                        if (hasTowns) {
                            if (!confirm(
                                    'Esta zona tiene pueblos asociados. Si eliminas la zona, también se eliminarán sus pueblos. ¿Deseas continuar?'
                                )) {
                                return;
                            }

                            // Eliminar pueblos asociados
                            document.querySelectorAll(`.town-row .town-zone option[value="${zoneId}"]`).forEach(
                                option => {
                                    const townRow = option.closest('.town-row');
                                    if (townRow && townRow.querySelector('.town-zone').value == zoneId) {
                                        townRow.remove();
                                    }
                                });
                        }

                        // Eliminar del array
                        const zoneIndex = zones.findIndex(z => z.id === zoneId);
                        if (zoneIndex !== -1) zones.splice(zoneIndex, 1);
                    }

                    // Marcar los elementos eliminados para el backend
                    if (row.hasAttribute('data-db-id')) {
                        const dbId = row.getAttribute('data-db-id');
                        const type = targetId.startsWith('city-row-') ? 'cities' : 
                                    targetId.startsWith('zone-row-') ? 'zones' : 'towns';
                        
                        // Agregar un campo oculto para indicar que este elemento debe eliminarse
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `${type}_to_delete[]`;
                        input.value = dbId;
                        document.getElementById('shipping-form').appendChild(input);
                    }

                    // Eliminar la fila
                    row.remove();

                    // Actualizar selectores
                    if (targetId.startsWith('city-row-')) {
                        updateCitySelectors();
                    } else if (targetId.startsWith('zone-row-')) {
                        updateZoneSelectors();
                    }
                }
            }
        });

        // Escuchar cambios en nombres de ciudades y zonas
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('city-name')) {
                updateCitySelectors();
            } else if (e.target.classList.contains('zone-name')) {
                updateZoneSelectors();
            }
        });

        // Escuchar cambios en selección de ciudad para zonas
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('zone-city')) {
                updateZoneSelectors();
            }
        });

        // Registrar listeners para botones de agregar
        document.getElementById('add-city-row').addEventListener('click', function() {
            addCityRow();
        });

        document.getElementById('add-zone-row').addEventListener('click', function() {
            addZoneRow();
        });

        document.getElementById('add-town-row').addEventListener('click', function() {
            addTownRow();
        });

        // Procesar el formulario antes de enviar
        document.getElementById('shipping-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Detener el envío normal

            // Validar que haya al menos una ciudad
            if (document.querySelectorAll('.city-row').length === 0) {
                alert('Debes agregar al menos una ciudad');
                return false;
            }

            // Reorganizar datos para envío
            const formData = processFormDataForSubmission();

            // Enviar datos usando fetch
            submitFormData(formData);
        });

        // Procesar datos para envío
        function processFormDataForSubmission() {
            const formData = new FormData(document.getElementById('shipping-form'));

            // Eliminar los datos planos de ciudades, zonas y pueblos
            const cityRows = document.querySelectorAll('.city-row');
            const zoneRows = document.querySelectorAll('.zone-row');
            const townRows = document.querySelectorAll('.town-row');

            // Crear estructura jerárquica
            const processedCities = [];

            cityRows.forEach((cityRow) => {
                const cityId = parseInt(cityRow.id.split('-')[2]);
                const dbCityId = cityRow.hasAttribute('data-db-id') ? cityRow.getAttribute('data-db-id') : null;
                
                const cityData = {
                    nombre: cityRow.querySelector('.city-name').value,
                    precio: cityRow.querySelector('input[name$="[precio]"]').value,
                    zones: []
                };
                
                // Si existe un ID de base de datos, incluirlo
                if (dbCityId) {
                    cityData.id = dbCityId;
                }

                // Agregar zonas asociadas a esta ciudad
                const cityZones = Array.from(zoneRows).filter(zoneRow => {
                    const citySelect = zoneRow.querySelector('.zone-city');
                    return parseInt(citySelect.value) === cityId;
                });

                cityZones.forEach((zoneRow) => {
                    const zoneId = parseInt(zoneRow.id.split('-')[2]);
                    const dbZoneId = zoneRow.hasAttribute('data-db-id') ? zoneRow.getAttribute('data-db-id') : null;
                    
                    const zoneData = {
                        nombre: zoneRow.querySelector('.zone-name').value,
                        latitud: zoneRow.querySelector('input[name$="[latitud]"]').value,
                        longitud: zoneRow.querySelector('input[name$="[longitud]"]').value,
                        towns: []
                    };
                    
                    // Si existe un ID de base de datos, incluirlo
                    if (dbZoneId) {
                        zoneData.id = dbZoneId;
                    }

                    // Agregar pueblos asociados a esta zona
                    const zoneTowns = Array.from(townRows).filter(townRow => {
                        const zoneSelect = townRow.querySelector('.town-zone');
                        return parseInt(zoneSelect.value) === zoneId;
                    });

                    zoneTowns.forEach((townRow) => {
                        const dbTownId = townRow.hasAttribute('data-db-id') ? townRow.getAttribute('data-db-id') : null;
                        
                        const diasSelect = townRow.querySelector('select[name$="[dias_disponibles][]"]');
                        const diasSeleccionados = Array.from(diasSelect.selectedOptions).map(
                            option => option.value);

                        const townData = {
                            nombre: townRow.querySelector('input[name$="[nombre]"]').value,
                            latitud: townRow.querySelector('input[name$="[latitud]"]').value,
                            longitud: townRow.querySelector('input[name$="[longitud]"]').value,
                            dias_disponibles: diasSeleccionados
                        };
                        
                        // Si existe un ID de base de datos, incluirlo
                        if (dbTownId) {
                            townData.id = dbTownId;
                        }

                        zoneData.towns.push(townData);
                    });

                    cityData.zones.push(zoneData);
                });

                processedCities.push(cityData);
            });

            // Obtener elementos marcados para eliminar
            const citiesToDelete = Array.from(document.querySelectorAll('input[name="cities_to_delete[]"]')).map(input => input.value);
            const zonesToDelete = Array.from(document.querySelectorAll('input[name="zones_to_delete[]"]')).map(input => input.value);
            const townsToDelete = Array.from(document.querySelectorAll('input[name="towns_to_delete[]"]')).map(input => input.value);

            // Eliminar los campos antiguos y agregar los nuevos en formato jerárquico
            Array.from(formData.keys()).forEach(key => {
                if (key.startsWith('cities[') || key.startsWith('zones[') || key.startsWith('towns[') || 
                    key.startsWith('cities_to_delete') || key.startsWith('zones_to_delete') || key.startsWith('towns_to_delete')) {
                    formData.delete(key);
                }
            });

            // Añadir el token CSRF y método PUT
            const csrfToken = document.querySelector('input[name="_token"]').value;
            formData.append('_token', csrfToken);
            formData.append('_method', 'PUT');

            // Añadir las ciudades procesadas
            formData.append('cities', JSON.stringify(processedCities));
            
            // Añadir los IDs a eliminar
            if (citiesToDelete.length > 0) formData.append('cities_to_delete', JSON.stringify(citiesToDelete));
            if (zonesToDelete.length > 0) formData.append('zones_to_delete', JSON.stringify(zonesToDelete));
            if (townsToDelete.length > 0) formData.append('towns_to_delete', JSON.stringify(townsToDelete));

            return formData;
        }

        // Enviar datos usando fetch
        function submitFormData(formData) {
            const url = document.getElementById('shipping-form').getAttribute('action');

            fetch(url, {
                    method: 'POST', // Se mantiene POST porque el método real va en _method
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(`Error: ${data.error}`);
                    } else {
                        // Redireccionar en caso de éxito
                        alert('Compañía actualizada con éxito');
                        window.location.href = data.redirect_url || '{{ route("admin.shipping-companies.index") }}';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ha ocurrido un error al procesar el formulario.');
                });
        }

        // Inicializar con los datos existentes
        initializeWithExistingData();
    </script>
@endsection