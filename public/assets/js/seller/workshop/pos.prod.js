import { createApp } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
// import { createApp } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.prod.js'

const app = createApp({
    template: `
        <ErrorModal :error-message="errorMessage"/>
        <SuccessModal :success-message="successMessage"/>
        <CreateClientModal />
        <div class="row h-100">
            <div class="col-12 col-lg-8">
                <Heading
                    @update-search="updateSearch"
                    @change-category="updateCategory"
                    @change-brand="updateBrand"
                    :categories="categories"
                    :brands="brands"
                />

                <transition name="fade">
                    <Loader v-if="isLoading"/>

                    <div class="col-12 w-100 p-0" v-else>
                      <ProductsList v-if="products.length > 0" :products="filteredProducts" @product-selected="addToCart"/>
                      <div v-else-if="dataLoaded">
                            NO HAY PRODUCTOS
                      </div>
                    </div>

                </transition>
            </div>
            <div class="col-12 col-lg-4">
              <CartData :customers="customers" :cart-data="cartData" :isLoadingCart="isLoadingCart" @user-change="handleUserChange"/>
            </div>
        </div>
    `,

    data() {
        return {
            isLoading: true,
            dataLoaded: false,
            isLoadingCart: false,
            isAddingProduct: false,
            selectedUser: null,
            errorMessage: "Error inesperado",
            successMessage: "finalizado con exito",
            products: [],
            categories: [],
            brands: [],
            customers: [],
            cartData: [],
            searchParam: '',
            brandId: '',
            categoryId: ''
        }
    },

    async mounted() {
        await this.fetchData();
    },

    provide(){
        return {
            addingProduct: this.isAddingProduct,
        }
    },

    methods: {
        async getProducts(){
            this.isLoading = true;
            this.dataLoaded = false;
            const response = await fetch(`${url}/api/v2/products?category_id=${this.categoryId}&brand_id=${this.brandId}`,{
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                }
            );

            const res = await response.json();

            if(res.success === true){
                const { data } = res;
                this.isLoading = false;
                setTimeout(() => {
                    this.products = data;
                    this.dataLoaded = true;
                }, 500);
            }
        },

        async getCustomers(){
            const response = await fetch(`${url}/api/v2/seller/pos/get-customers`,{
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                }
            );

            const res = await response.json();

            if(res.success === true){
                const { data } = res;
                this.customers = data;
            }
        },

        async getWorkshopAvailableCategories(){
            const response = await fetch(`${url}/api/v2/workshop/categories`,{
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                }
            );

            const res = await response.json();

            if(res.result === 'true'){
                const { data } = res;
                this.categories = data;
            }
        },

        async addToCart(product){
            if(this.isAddingProduct){
                return false;
            }

            if(this.selectedUser == null){
                this.errorMessage = "Debes seleccionar un cliente para realizar esta accion";
                $('#errorModal').modal('show');
                return false;
            }

            const body = {
                stock_id: product.stock_id
            }

            this.isAddingProduct = true;
            const response = await fetch(`${url}/api/v2/seller/pos/update-cart`,{
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(body)
                }
            );

            const res = await response.json();

            if(res.success === true){
                const { data } = res;
                this.isAddingProduct = false;
                setTimeout(() => {
                    //
                }, 500);
            }
        },

        async getBrands(){
            const response = await fetch(`${url}/api/v2/brands`,{
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                }
            );

            const res = await response.json();

            if(res.success === true){
                const { data } = res;
                this.brands = data;
            }
        },

        async updateSearch(value) {
            this.searchParam = value;
        },

        async updateCategory(value){
            this.categoryId = value;
            await this.getProducts();

        },

        async updateBrand(value){
            this.brandId = value;
            await this.getProducts();
        },

        async handleUserChange(id){
            this.isLoadingCart = true;
            this.selectedUser = id;

            const response = await fetch(`${url}/api/v2/seller/pos/user-cart-data`, {
                method: 'post',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ userId: this.selectedUser })
            });

            const res = await response.json();

            if(res.result === true){
                const { data } = res;
                this.isAddingProduct = false;
                setTimeout(() => {
                    this.cartData = data
                    this.isLoadingCart = false;
                }, 500);
            }
        },

        async fetchData(){
            await this.getProducts();
            await this.getWorkshopAvailableCategories();
            await this.getBrands();
            await this.getCustomers();
        }
    },

    computed: {
        filteredProducts() {
            if (this.searchParam.length > 0) {
                return this.products.filter(product =>
                    product.name.toUpperCase().includes(this.searchParam.toUpperCase()) || product.code?.includes(this.searchParam)
                );
            }
            return this.products;
        }
    }
});

app.component('Loader', {
    template: `
        <div id="preloader-delivery">
            <div id="loader-delivery"></div>
        </div>
    `,
});

app.component('ErrorModal', {
    props: ['errorMessage'],
    template: `
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-danger font-weight-bold" id="myModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ errorMessage }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `
});

app.component('SuccessModal', {
    props: ['successMessage'],
    template: `
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-success font-weight-bold" id="myModalLabel">Acción realizada con exito</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ successMessage }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `
});

app.component('Heading', {
    props: ['categories', 'brands'],

    data() {
        return {
            searchParam: '',
            brandId: '',
            categoryId: ''
        }
    },

    emits: ['update-search', 'change-category', 'change-brand'],

    watch: {
        searchParam(newValue) {
            this.$emit('update-search', newValue);
        },

        brandId(newValue){
            this.$emit('change-brand', newValue)
        },

        categoryId(newValue){
            this.$emit('change-category', newValue)
        }
    },

    template: `
        <div class="row mt-2 my-2">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h5 class="w-100 d-block"><i class="las la-fax icon-group"></i> Punto de Venta</h5>
                <button class="btn btn-success w-25 d-block rounded-15px" data-target="#add_servicios" data-toggle="modal" style="font-weight: bold;"><i class="las la-plus icon-group"></i> Agregar Servicios</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-4 my-2">
                <input v-model="searchParam" type="text" placeholder="Busca tus piezas aqui..." class="form-control">
            </div>
            <div class="col-12 col-lg-3 my-2">
                <select v-model="categoryId" class="form-control" id="category-selector" :disabled="categories.length < 1">
                    <option value="" selected v-if="categories.length < 1">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando categorias...</span>
                        </div>
                    </option>
                    <option value="" v-else selected>Todas las categorias</option>
                    <option v-for="(category, i) in categories" :key="i" :value="category.id">{{ category.name }}</option>
                </select>
            </div>
            <div class="col-12 col-lg-3 my-2">
                <select v-model="brandId" name="" id="branch-selector" class="form-control" :disabled="brands.length < 1">
                    <option value="" selected v-if="brands.length < 1">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando marcas...</span>
                        </div>
                    </option>
                  <option value="" v-else selected>Todas las marcas</option>
                    <option v-for="(brand, i) in brands" :key="i" :value="brand.id">
                        {{ brand.name.toLowerCase().charAt(0).toUpperCase() + brand.name.toLowerCase().slice(1) }}
                    </option>
                </select>
            </div>
        </div>
    `
})

app.component('CartData',{
    props: ['customers', 'cartData', 'isLoadingCart'],
    emits: ['user-change'],
    methods: {
        getPrice(cart) {
            let price = Number(cart.price.replace('RD$', '').replace(',', '').replace('.00', '')) * cart.cart_quantity
            price = price.toFixed(2)
            return `$RD ${price}`;
        }
    },
    data() {
        return {
            computedSubtotal: 0,
            discountPercent: 0,
        }
    },
    watch: {
        'cartData': {
            deep: true,
            handler(){
                let subtotal = 0;
                this.cartData?.cart_data?.data.forEach(cart => {
                    if (cart.price.startsWith('RD$')) {
                        let price = Number(cart.price.replace('RD$', '').replace(',', '')) * cart.cart_quantity;
                        subtotal += price;
                    }
                });
                this.computedSubtotal = Number(subtotal.toFixed(2)) - this.discount;
            }
        }
    },
    computed:{
        tax(){
            return this.computedSubtotal * 0.18;
        },
        discount(){
            return this.computedSubtotal * (this.discountPercent / 100);
        },
        total(){
            return this.computedSubtotal + this.tax - this.discount;
        }
    },
    template:`
        <div class="container rounded-15px border mt-5 m-lg-0 h-100">
            <div class="row mt-3">
              <div class="col-8">
                <select class="form-control" id="client-selector" :disabled="customers.length < 1" @change="$emit('user-change', $event.target.value)">
                    <option value="" selected v-if="customers.length < 1">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando clientes...</span>
                        </div>
                    </option>
                    <option value="" v-else selected disabled>Seleccionar cliente</option>
                  <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                      {{ customer.name + ' - ' + customer.email }}
                  </option>
                </select>
              </div>

              <div class="col-4">
                <button class="btn btn-success rounded-15px" data-target="#new-customer-modal" data-toggle="modal">
                    Crear cliente
                </button>
              </div>
            </div>

            <div class="aiz-pos-cart-list mb-4 mt-3 c-scrollbar-light">
                <ul class="list-group list-group-flush">
                    <li v-if="isLoadingCart" class="list-group-item py-0 pl-2 text-center">
                        <div class="spinner-border" role="status" >
                            <span class="sr-only">Loading...</span>
                        </div>
                    </li>
                    <li v-else-if="cartData?.cart_data?.data.length > 0" class="list-group-item py-0 pl-2">
                        <div v-for="cart in cartData?.cart_data?.data" :key="cart.id" class="row gutters-5 align-items-center">
                            <div class="col-auto w-60px">
                                <div class="row no-gutters align-items-center flex-column aiz-plus-minus">
                                    <button class="btn col-auto btn-icon btn-sm fs-15" type="button" data-type="plus" data-field="Cantidad">
                                        <i class="las la-plus"></i>
                                    </button>
                                    <input type="text" name="Cantidad" id="Cantidad" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" :value="cart.cart_quantity" min="1" max="5">
                                    <button class="btn col-auto btn-icon btn-sm fs-15" type="button" data-type="minus" data-field="Cantidad">
                                        <i class="las la-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col">
                                <div class="text-truncate-2">{{ cart.product_name }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="fs-12 opacity-60">{{ cart.price }} x {{ cart.cart_quantity }}</div>
                                <div class="fs-15 fw-600">{{ getPrice(cart) }}</div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-circle btn-icon btn-sm btn-soft-danger ml-2 mr-0">
                                    <i class="las la-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li v-else class="list-group-item py-0 pl-2">
                        <i class="bi bi-emoji-frown"></i>
                        <p class="text-center">No hay productos en el carrito</p>
                    </li>
                </ul>
            </div>

            <div class="row justify-content-between">
                <div class="col-6 ml-2">
                   <h5 class="fs-14 font-weight-bold">Subtotal</h5>
                   <h5 class="fs-14 font-weight-bold">I.T.B.I.S</h5>
                   <h5 class="fs-14 font-weight-bold">Descuento</h5>
                   <hr>
                   <h5 class="fs-16 font-weight-bold mt-2">Total</h5>
                   <hr>
                </div>
                <div class="col-4 mr-1">
                   <span class="d-block fs-14">RD$ {{ computedSubtotal }}</span>
                   <span class="d-block fs-14">RD$ {{ tax }}</span>
                   <span class="d-block fs-14">RD$ {{ discount }}</span>
                   <hr>
                   <span class="d-block fs-16 mt-2 font-weight-bold">RD$ {{ total }}</span>
                   <hr>
                </div>
            </div>

            <div class="row justify-content-end mb-4 mr-1">
              <button class="btn btn-primary rounded-15px" data-target="#order-confirm-modal" data-toggle="modal">Confirmar Pedido</button>
            </div>
        </div>
    `
});

app.component('ProductsList',{
    props: ['products'],
    emits: ['product-selected'],
    methods: {
        handleProductSelected(product){
            this.$emit('product-selected', product)
        }
    },
    template: `
        <div class="row w-100 justify-content-around m-auto overflow-hidden">
            <ProductItem v-for="(product, i) in this.products"
                         :key="product.id"
                         :product="product"
                         @product-selected="handleProductSelected"
            />
        </div>
    `
});

app.component('ProductItem',{
    props: ['product'],
    inject: ['addingProduct'],
    emits: ['product-selected'],
    methods: {
        emitClickedProduct(product){
            this.$emit('product-selected', product)
        }
    },
    template: `

        <div class="col-6 col-lg-3 p-1 w-100" @click="emitClickedProduct(product)">
            <div class="card bg-white c-pointer product-card hov-container w-100 h-100 shadow-lg border rounded-10px product">
            <span class="absolute-bottom-right mt-1 ml-1 mr-0">
                    <span v-if="product.stock > 0" class="badge badge-inline badge-success fs-12 mb-1 mr-1">Quedan: {{ product.stock }}</span>
                    <span v-else class="badge badge-inline badge-danger fs-12 mb-1 mr-1">Agotado</span>
            </span>
                <img :src="productImage" :alt="product.name" class="card-img" style="padding: 7%;">
                <div class="flex-column align-items-center justify-content-between" style=" padding: 20px 16px; border-radius: 4px;">
                    <h6 class="text-capitalize d-block w-100">{{ product.name }}</h6>

                    <h6 class=" font-weight-bold d-block w-100 mb-3 mt-2">{{ product.main_price }}</h6>
                    <p><span style="font-weight: bold;">Categoría: </span>Motor/Juntas</p>
                </div>
                <div class="card-info" v-if="addingProduct">
                    <div class="spinner-border" role="status" >
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="card-info" v-else>
                    <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    <p>Agregar al carrito</p>
                </div>
            </div>
        </div>
    `,

    computed: {
        productImage(){
            if(this.product.thumbnail_image == null || this.product.thumbnail_image.length < 15){
                return "https://lapieza.do/public/assets/img/placeholder.jpg";
            }else{
                return this.product.thumbnail_image;
            }
        }
    }
})

app.component('CreateClientModal', {
    emits: ['create_user'],
    data() {
        return {
            formData: {
                name: '',
                email: '',
                phone: ''
            },
            isValidEmail: null,
            isValidPhone: null
        }
    },
    methods: {
        confirmForm(){
            this.$emit('create_user', this.formData);
        },
        async validateEmail(email){
            const response = await fetch(`${url}/email/check`,{
                method: 'post',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email
                })
            })

            const res = await response.json();

            this.isValidEmail = !res.exists;
        },
        async validatePhone(phone){
            const response = await fetch(`${url}/phone/check`,{
                method: 'post',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    phone,
                    user_type: 'customer'
                })
            })

            const res = await response.json();

            return !res.exists;
        }
    },
    watch:{
        'formData.email': async function (newEmail) {
            this.isValidEmail = await this.validateEmail(newEmail);
        },
        'formData.phone': async function (newPhone) {
            this.isValidPhone = await this.validatePhone(newPhone);
        }
    },
    template: `
        <div id="new-customer-modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
                <div class="modal-content">
                    <div class="modal-header bord-btm">
                        <h4 class="modal-title h6">Agregar Nuevo Cliente</h4>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="shipping_form">
                        <div class="modal-body" id="shipping_address">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="name">Nombre</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Nombre" id="name" name="name" class="form-control" v-model="formData.name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="email">Correo electrónico</label>
                                    <div class="col-sm-10">
                                        <input type="email"
                                               placeholder="Correo electrónico"
                                               id="email"
                                               name="email"
                                               :class="{
                                                  'is-valid': isValidEmail,
                                                  'is-invalid': !isValidEmail
                                               }"
                                               class="form-control"
                                               v-model="formData.email"
                                               required>
                                        <span v-if="!isValidEmail" class="invalid-feedback">Este correo ya esta siendo usado</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="phone">Teléfono</label>
                                    <div class="col-sm-10">
                                        <input type="number"
                                               min="0"
                                               placeholder="Teléfono"
                                               id="phone"
                                               name="phone"
                                               :class="{
                                                  'is-valid': isValidPhone,
                                                  'is-invalid': !isValidPhone
                                               }"
                                               class="form-control"
                                               v-model="formData.phone"
                                               required>
                                    </div>
                                    <span v-if="!isValidPhone" class="invalid-feedback">Este número telefonico ya esta en uso</span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-styled btn-soft-dark rounded-15px" data-dismiss="modal" id="close-button">
                            Cerrar
                        </button>
                        <button type="button"
                                class="btn btn-primary btn-styled btn-base-1 rounded-15px"
                                id="confirm-address"
                                data-target="#register-confirm-modal"
                                data-toggle="modal"
                                data-dismiss="modal"
                                :disabled="formData.name === '' || formData.email === '' || formData.phone === '' && isValidPhone && isValidEmail"
                                @click="confirmForm">
                          Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `
})

app.mount('#app')
