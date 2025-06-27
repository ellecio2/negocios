import { createApp } from "https://unpkg.com/vue@3/dist/vue.esm-browser.prod.js";

const app = createApp({
    template: `
        <transition name="fade">
            <Loader v-if="isLoading"/>
            <div v-else-if="dataLoaded">
                <StoreCard v-for="(store, i) in data" :key="i" :storeId="Object.keys(store)[0]" :store="store"/>
            </div>
        </transition>
    `,

    data() {
        return {
            isLoading: true,
            dataLoaded: false,
            data: null,
        };
    },

    methods: {
        async getDeliveryData() {
            const response = await fetch(`${url}/api/v1/delivery/pricing`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            });

            const res = await response.json();

            this.isLoading = false;

            if (res.result === true) {
                const { data } = res;
                this.data = data;

                setTimeout(() => {
                    this.dataLoaded = true;
                }, 500);
            }
        },
    },

    mounted() {
        this.getDeliveryData();
    },
});

app.component("Loader", {
    template: `
                <div id="preloader-delivery">
                    <div id="loader-delivery"></div>
                </div>
              `,
});

app.component("StoreCard", {
    props: ["store", "storeId"],
    template: `
        <!-- Tarjeta de tienda completa -->
        <div class="rowe mb-20 mt-20">
            <!-- Header de tienda -->
            <CardHeader :data="store[storeId]"/>
            <!-- Header de tienda -->

            <DeliveryOption v-if="store[storeId].transporteBlanco.productsPackage.length > 0" :store="store[storeId]" type="tb"/>
            <DeliveryOption v-if="store[storeId].PedidosYa.productsPackage.length > 0" :store="store[storeId]" type="py"/>
        </div>
        <!-- Tarjeta de tienda completa -->
    `,
    computed: {
        haveTransporteBlancoProducts() {
            return true;
        },
    },
});

app.component("CardHeader", {
    props: ["data"],
    template: `
        <div class="col-sm-12 my-3">
            <div class="rowe">
                <div class="col-md-2">
                    <div class="box-header with-border">
                        <img class="img-logo" :src="data.deliveryInfo.logo" alt="Photo">
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="nav navbar-nav">
                        <li style="font-weight: bold;">
                            <a class="fa fa-map-marker"></a>
                            Punto de Origen
                        </li>
                        <li>
                            {{ data.deliveryInfo.pickupPoint }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="nav navbar-nav">
                        <li style="font-weight: bold;">
                            <a class="fa fa-map-marker"></a>
                            Punto de Destino
                        </li>
                        <li>
                            {{ data.deliveryInfo.dropoffPoint }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    `,
});

app.component("DeliveryOption", {
    props: ["store", "type"],
    template: `
        <!-- Contenido informativo izquierdo -->
        <div class="col-sm-7">
            <div class="box-header with-border stilo-productos">
                <h6 class="box-title custom">
                    <span>Productos en el Pedido</span>
                    <button v-if="type === 'tb'" class="btn-tooltip" data-toggle="tooltip" data-placement="bottom" title="Estos productos son demasiado grandes o pesados. Solo pueden ser enviados por Transporte Blanco.">
                        ?
                    </button>
                    <button v-else class="btn-tooltip" data-toggle="tooltip" data-placement="bottom" title="Estos productos pueden ser enviados de forma inmediata por Pedidos Ya. Aún asi puedes elegir Transporte Blanco.">
                        ?
                    </button>
                </h6>
            </div>
            <ProductsSection :products="products" />
        </div>
        <!-- Contenido informativo izquierdo -->
        <DeliveryDetail :store="store" :type="type" :storeId="Object.keys(store)" :products="products" />
    `,
    computed: {
        products() {
            if (this.type == "py") {
                return this.store.PedidosYa.productsPackage;
            } else if (this.type == "tb") {
                return this.store.transporteBlanco.productsPackage;
            }
        },
    },
});

app.component("DeliveryDetail", {
    props: ["store", "type", "storeId", "products"],
    data() {
        return {
            isTransporteBlanco: false,
            activeItem: "",
            pickupPointId: this.store.pickupPoint.id,
        };
    },
    template: `
    <!-- Información del delivery -->
        <div class="col-5">
            <div class="box">
                <!-- box-header -->
                <div class="box-header with-border">
                    <h6 class="box-title">Selecciona tu medio de envio</h6>
                </div><!-- Box header END-->

                <!-- Box Body -->
                <div class="box-body" v-if="!isTransporteBlanco">
                    <!-- Nav Tabs -->
<!--                    <ul class="nav nav-pills justify-content-center mb-20 centro">
                        <li class="nav-item">
                            <a @click="setActiveItem('Pedidos Ya')" class="nav-link btn-primary rounded-15p btn-sm" :class="{ active: activeItem === 'Pedidos Ya'}"  style="font-weight: bold;">
                                Pedidos Ya
                            </a>

                        </li>
                        <li class="nav-item">
                            <a @click="setActiveItem('TB + PY')" class="nav-link btn-primary rounded-15p btn-sm" :class="{ active: activeItem === 'TB + PY'}" style="font-weight: bold;">
                               Transporte Blanco
                            </a>
                        </li>
                        <li class="nav-item">
                            <a @click="setActiveItem('Recogida Local')" class="nav-link btn-primary rounded-15p btn-sm" :class="{ active: activeItem === 'Recogida Local'}" style="font-weight: bold;">
                              Recogida Local
                            </a>
                        </li>
                    </ul>  Nav Tabs -->
                    <ul class="nav nav-pills mb-20 centro">
                        <li class="nav-item">
                            <input
                                type="radio"
                                id="pedidosYa"
                                @click.stop="setActiveItem('Pedidos Ya')"
                                :checked="activeItem === 'Pedidos Ya'"
                                style="margin-right: 5px;"
                                class="btn-check "
                            >
                            <label for="pedidosYa"  :class="{ active: activeItem === 'Pedidos Ya'}" style="font-weight: bold;">
                                Pedidos Ya
                            </label>
                        </li>
                        <li class="nav-item">
                            <input
                                type="radio"
                                id="transporteBlanco"
                                @click.stop="setActiveItem('TB + PY')"
                                :checked="activeItem === 'TB + PY'"
                                class="btn-check "
                                style="margin-right: 5px;"
                            >
                            <label for="transporteBlanco"  :class="{ active: activeItem === 'TB + PY'}" style="font-weight: bold;">
                                Transporte Blanco
                            </label>
                        </li>
                        <li class="nav-item">
                            <input
                                type="radio"
                                id="recogidaLocal"
                                @click.stop="setActiveItem('Recogida Local')"
                                :checked="activeItem === 'Recogida Local'"
                                class="btn-check "
                                style="margin-right: 5px;"
                            >
                            <label for="recogidaLocal"  :class="{ active: activeItem === 'Recogida Local'}" style="font-weight: bold;">
                                Recogida Local
                            </label>
                        </li>
                    </ul> <!-- Nav Tabs -->

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div  class="tab-pane" :class="{ active: activeItem === 'Pedidos Ya'}">
                            <div class="rowe">
                                <div class="col-md-4">
                                    <img :src="store.PedidosYa.logo" class="img-fluid-pedidos" :alt="Object.keys(store.PedidosYa)">
                                </div>
                                <div class="col-md-8">
                                    <ul class="nav navbar-nav" style="margin-top: 5%;">
                                        <li style="font-weight: bold;">
                                            <a class="fa fa-map-marker"></a>
                                            {{ store.deliveryInfo.city }}
                                        </li>
                                        <li>Costo Estimado de Envío</li>
                                        <li style="font-weight: bold;">
                                            {{ pyStartedPrice }} <!-- - {{ pyEndingPrice }}-->
                                        </li>
                                        <li>
                                            <a class="fa fa-truck"></a>
                                            Recíbelo
                                        </li>
                                        <li style="font-weight: bold;">
                                            {{ pyEstimatedPickup }} - {{ pyEstimatedDropOff }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div  class="tab-pane" :class="{ active: activeItem === 'TB + PY'}" >
                            <div class="rowe">
                                <div class="col-md-4">
                                    <img :src="store.transporteBlanco.logo" class="img-fluid-transporte" :alt="Object.keys(store.transporteBlanco)">
                                </div>
                                <div class="col-md-8">
                                    <ul class="nav navbar-nav" style="margin-top: 5%;">
                                        <li style="font-weight: bold;">
                                            <a class="fa fa-map-marker"></a>
                                            {{ store.deliveryInfo.city }}
                                        </li>
                                        <li>Costo Estimado de Envío</li>
                                        <li style="font-weight: bold;">
                                            {{ pytbStartedPrice }} - {{ pytbEndingPrice }}
                                        </li>
                                        <li>
                                            <a class="fa fa-truck"></a>
                                            Recíbelo
                                        </li>
                                        <li style="font-weight: bold;">
                                            {{ pytbEstimatedDelivery }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div  class="tab-pane" :class="{ active: activeItem === 'Recogida Local'}" >
                            <div class="rowe">
                                <div class="col-md-4">
                                    <img :src="store.deliveryInfo.logo" class="img-fluid-pickup" alt="store-name">
                                </div>
                                <div class="col-md-8" style="margin-top: 5%;">
                                    {{ store.pickupPoint.address }}
                                    <br>Telf: {{ store.pickupPoint.phone }} y Whatsapp: {{ store.pickupPoint.phone }}
                                </div>
                            </div>
                        </div>
                    </div><!-- Tab Panes END -->
                </div><!-- Box Body END -->

                <!-- Box Body -->
                <div class="box-body" v-else>
                    <!-- Nav Tabs -->
<!--                    <ul class="nav nav-pills justify-content-center mb-20 centro">
                        <li class="nav-item">
                            <a @click="setActiveItem('Transporte Blanco')" class="nav-link" :class="{ active: activeItem === 'Transporte Blanco'}" style="font-weight: bold;">
                                Transporte Blanco
                            </a>
                        </li>
                        <li class="nav-item">
                            <a @click="setActiveItem('Recogida Local')" class="nav-link" :class="{ active: activeItem === 'Recogida Local'}" style="font-weight: bold;">
                                Recogida Local
                            </a>
                        </li>
                    </ul>--> <!-- Nav Tabs -->
                    <ul class="nav nav-pills mb-20 centro">
                        <li class="nav-item">
                            <input
                                type="radio"
                                id="transporte-blanco"
                                value="Transporte Blanco"
                                v-model="activeItem"
                                @change="setActiveItem('Transporte Blanco')"
                                class="btn-check "
                                autocomplete="off"
                                style="margin-right: 5px;"
                            >
                            <label
                                for="transporte-blanco"

                                :class="{ active: activeItem === 'Transporte Blanco'}"
                                style="font-weight: bold;">
                                Transporte Blanco
                            </label>
                        </li>
                        <li class="nav-item">
                            <input
                                type="radio"
                                id="recogida-local"
                                value="Recogida Local"
                                v-model="activeItem"
                                @change="setActiveItem('Recogida Local')"
                                class="btn-check"
                                autocomplete="off"
                                style="margin-right: 5px;"
                            >
                            <label
                                for="recogida-local"

                                :class="{ active: activeItem === 'Recogida Local'}"
                                style="font-weight: bold;">
                                Recogida Local
                            </label>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane" :class="{ active: activeItem === 'Transporte Blanco'}" >
                            <div class="rowe">
                                <div class="col-md-4">
                                    <img :src="store.transporteBlanco.logo" class="img-fluid-transporte" :alt="Object.keys(store.transporteBlanco)">
                                </div>
                                <div class="col-md-8">
                                    <ul class="nav navbar-nav" style="margin-top: 5%;">
                                        <li style="font-weight: bold;">
                                            <a class="fa fa-map-marker"></a>
                                            {{ store.deliveryInfo.city }}
                                        </li>
                                        <li>Costo Estimado de Envío</li>
                                        <li style="font-weight: bold;">
                                            {{ tbStartedPrice }} - {{ tbEndingPrice }}
                                        </li>
                                        <li>
                                            <a class="fa fa-truck"></a>
                                            Recíbelo
                                        </li>
                                        <li style="font-weight: bold;">
                                            {{ tbEstimatedDelivery }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div  class="tab-pane" :class="{ active: activeItem === 'Recogida Local'}" >
                            <div class="rowe">
                                <div class="col-md-4">
                                    <img :src="store.deliveryInfo.logo" class="img-fluid-pickup" alt="store-name">
                                </div>
                                <div class="col-md-8" style="margin-top: 5%;">
                                    {{ store.pickupPoint.address }}
                                    <br>Telf: {{ store.pickupPoint.phone }} y Whatsapp: {{ store.pickupPoint.phone }}
                                </div>
                            </div>
                        </div>
                    </div><!-- Tab Panes END -->
                </div><!-- Box Body END -->
            </div><!-- Box -->
        </div>
    `,
    computed: {
        tbStartedPrice() {
            return (
                "RD$" +
                this.store.transporteBlanco?.pricing?.delivery?.initialCost.toFixed(
                    2,
                )
            );
        },
        tbEndingPrice() {
            return (
                "RD$" +
                this.store.transporteBlanco?.pricing?.delivery?.endingCost.toFixed(
                    2,
                )
            );
        },
        tbEstimatedDelivery() {
            return this.store.transporteBlanco?.pricing?.delivery
                ?.estimatedDeliveryTime;
        },
        pyStartedPrice() {
            return (
                "RD$" +
                this.store.PedidosYa?.pricing?.delivery?.initialCost.toFixed(2)
            );
        },
        pyEndingPrice() {
            return (
                "RD$" +
                this.store.PedidosYa?.pricing?.delivery?.endingCost.toFixed(2)
            );
        },
        pyEstimatedPickup() {
            return this.store.PedidosYa?.pricing?.delivery?.estimatedPickupTime;
        },
        pyEstimatedDropOff() {
            return this.store.PedidosYa?.pricing?.delivery
                ?.estimatedDeliveryTime;
        },
        pytbStartedPrice() {
            return (
                "RD$" +
                this.store.PedidosYa?.pricing?.transporteBlanco?.initialCost.toFixed(
                    2,
                )
            );
        },
        pytbEndingPrice() {
            return (
                "RD$" +
                this.store.PedidosYa?.pricing?.transporteBlanco?.endingCost.toFixed(
                    2,
                )
            );
        },
        pytbEstimatedDelivery() {
            return this.store.PedidosYa?.pricing?.transporteBlanco
                ?.estimatedDeliveryTime;
        },
    },
    methods: {
        checkType() {
            if (this.type == "tb") {
                this.isTransporteBlanco = true;
            }
        },

        setActiveItemOnLoad() {
            if (this.type === "py") {
                this.activeItem = "Pedidos Ya";
            } else if (this.type === "tb") {
                this.activeItem = "Transporte Blanco";
            }

            if (this.activeItem === "Pedidos Ya") {
                let cost = this.pyStartedPrice;
                cost = Number(cost.replace("RD$", ""));
                /*let percentage = Math.random() * (0.1 - 0.05) + 0.05;
                cost = cost + cost * percentage;*/

                this.products.forEach((product) => {
                    this.setDeliveryOptionInCart(
                        product,
                        cost,
                        "carrier",
                        "PEDIDOS YA",
                    );
                });
                return;
            }

            if (this.activeItem === "TB + PY") {
                let cost = this.pytbStartedPrice;
                cost = Number(cost.replace("RD$", ""));
                let percentage = Math.random() * (0.1 - 0.05) + 0.05;
                cost = cost + cost * percentage;

                this.products.forEach((product) => {
                    this.setDeliveryOptionInCart(
                        product,
                        cost,
                        "carrier",
                        "TB + PY",
                    );
                });
                return;
            }

            if (this.activeItem === "Transporte Blanco") {
                let cost = this.tbStartedPrice;
                cost = Number(cost.replace("RD$", ""));
                let percentage = Math.random() * (0.1 - 0.05) + 0.05;
                cost = cost + cost * percentage;

                this.products.forEach((product) => {
                    this.setDeliveryOptionInCart(
                        product,
                        cost,
                        "carrier",
                        "TRANSPORTE BLANCO",
                    );
                });
                return;
            }

            if (this.activeItem === "Recogida Local") {
                this.products.forEach((product) => {
                    this.setPickupOptionOptionInCart(
                        product,
                        "pickup point",
                        this.pickupPointId,
                    );
                });
                return;
            }
        },

        setActiveItem(item) {
            this.activeItem = item;

            if (this.activeItem === "Pedidos Ya") {
                let cost = this.pyStartedPrice;
                cost = Number(cost.replace("RD$", ""));
                /*let percentage = Math.random() * (0.1 - 0.05) + 0.05;
                cost = cost + cost * percentage;*/

                this.products.forEach((product) => {
                    this.setDeliveryOptionInCart(
                        product,
                        cost,
                        "carrier",
                        "PEDIDOS YA",
                    );
                });
                return;
            }

            if (this.activeItem === "TB + PY") {
                let cost = this.pytbStartedPrice;
                cost = Number(cost.replace("RD$", ""));
                let percentage = Math.random() * (0.1 - 0.05) + 0.05;
                cost = cost + cost * percentage;

                this.products.forEach((product) => {
                    this.setDeliveryOptionInCart(
                        product,
                        cost,
                        "carrier",
                        "TB + PY",
                    );
                });
                return;
            }

            if (this.activeItem === "Transporte Blanco") {
                let cost = this.tbStartedPrice;
                cost = Number(cost.replace("RD$", ""));
                let percentage = Math.random() * (0.1 - 0.05) + 0.05;
                cost = cost + cost * percentage;

                this.products.forEach((product) => {
                    this.setDeliveryOptionInCart(
                        product,
                        cost,
                        "carrier",
                        "TRANSPORTE BLANCO",
                    );
                });
                return;
            }

            if (this.activeItem === "Recogida Local") {
                this.products.forEach((product) => {
                    this.setPickupOptionOptionInCart(
                        product,
                        "pickup point",
                        this.pickupPointId,
                    );
                });
                return;
            }
        },

        async setDeliveryOptionInCart(product, cost, type, company) {
            const body = {
                type,
                shippingCompany: company,
                shippingCost: cost,
            };

            fetch(
                `${url}/api/v2/carts/${product.cart_id}/set-delivery-option`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify(body),
                },
            );
        },

        async setPickupOptionOptionInCart(product, type, pickupPointId) {
            const body = { type, pickupPointId };

            fetch(
                `${url}/api/v2/carts/${product.cart_id}/set-delivery-option`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify(body),
                },
            );
        },
    },

    mounted() {
        this.checkType();
        this.setActiveItemOnLoad();
    },
});

app.component("ProductDetail", {
    props: {
        product: {
            type: Object,
            required: true,
        },
    },
    template: `
        <div class="col-md-6">
            <div class="rowe">
                <div class="col-md-4">
                    <img :src="product.thumbnail" class="img-fluido" :alt="product.name">
                </div>
                <div class="col-md-8">
                    <ul class="nav navbar-nav">
                        <li style="font-weight: bold;">x{{ product.quantity }}</li>
                        <li style="font-weight: bold;">
                          {{ product.name }}
                        </li>
                        <li style="font-weight: bold;">
                            {{ product.model }}
                        </li>
                        <li style="font-weight: bold;">{{ product.price }}</li>
                    </ul>
                </div>
            </div>
        </div>
    `,
});

app.component("ProductsSection", {
    props: ["products"],
    template: `
        <div class="col-md-12">
            <div class="rowe">
                <ProductDetail v-for="(product, index) in products" :key="index" :product="product" />
            </div>
        </div>
    `,
});

app.mount("#app");
