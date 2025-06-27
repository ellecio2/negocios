<script>
import { ref } from "vue";

export default {
    props: {
        category: {
            type: Array,
            required: true,
        },
        isVisible: Boolean,
    },
    data() {
        return {
            selectedCategory: "",
            selectedBrand: "",
            selectedModel: "",
            selectedYear: "",
            chasis_serial: "",
            filteredBrands: [],
            filteredMarcs: [],
            years: [],
        };
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        filterBrands() {
            const searchTerm = this.selectedBrand.toLowerCase();
            this.filteredBrands = this.filteredBrandsBackup.filter((brand) =>
                brand.name.toLowerCase().includes(searchTerm),
            );
        },
        selectBrand(brand) {
            this.selectedBrand = brand.name;
            this.filteredBrands = [];
            this.loadMarcs();
        },

        filterModels() {
            const searchTerm = this.selectedModel.toLowerCase();
            this.filteredMarcs = this.filteredMarcsBackup.filter((model) =>
                model.model.toLowerCase().includes(searchTerm),
            );
        },
        selectModel(model) {
            this.selectedModel = model.model;
            this.filteredMarcs = [];
        },
        loadBrands() {
            const marcaId = this.selectedCategory;
            this.filteredBrands = [];
            this.selectedBrand = "";
            this.selectedModel = "";
            this.selectedYear = "";

            fetch(`get-marca/${marcaId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.length > 0) {
                        this.filteredBrands = data;
                    } else {
                        this.filteredBrands = [
                            { id: "", name: "No hay marcas disponibles" },
                        ];
                    }
                })
                .catch((error) => {
                    console.error("Error al cargar las marcas:", error);
                });
        },
        loadMarcs() {
            const brandId = this.selectedBrand;
            this.filteredMarcs = [];
            this.years = [];
            this.selectedModel = "";
            this.selectedYear = "";

            fetch(`get-model/${brandId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.brands && data.brands.length > 0) {
                        this.filteredMarcs = data.brands;
                    } else {
                        this.filteredMarcs = [
                            { id: "", model: "No hay modelos disponibles" },
                        ];
                    }

                    if (data.years && data.years.length > 0) {
                        this.years = data.years;
                    } else {
                        this.years = [
                            { id: "", year: "No hay años disponibles" },
                        ];
                    }
                })
                .catch((error) => {
                    console.error("Error al cargar los modelos:", error);
                });
        },
        async submitForm() {
            if (!this.$refs.articleForm) {
                console.error("Formulario no encontrado");
                return;
            }

            if (!this.selectedCategory || !this.selectedBrand) {
                Swal.fire({
                    type: "warning",
                    title: "Campos incompletos",
                    text: "Por favor, complete los campos obligatorios. (tipo, marca)",
                });
                return;
            }

            const formData = new FormData();
            formData.append("category_id", this.selectedCategory);
            formData.append("product_id", this.selectedBrand);
            formData.append("model_id", this.selectedModel);
            formData.append("year", this.selectedYear);
            formData.append("chasis_serial", this.chasis_serial);

            try {
                const response = await axios.post(`article/store`, formData, {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (response.data.status === true) {
                    this.$emit("close");
                    this.$emit("articleAdded");
                    Swal.fire({
                        type: "success",
                        title: "Bien",
                        text: response.data.message,
                        timer: 4000,
                    });
                } else {
                    Swal.fire({
                        type: "error",
                        title: "Opp...",
                        text: response.data.message,
                        timer: 4000,
                    });
                }
            } catch (error) {
                Swal.fire({
                    type: "error",
                    title: "Error",
                    text: "Ocurrió un error. Intente de nuevo.",
                });
                console.error("Error:", error);
            }
        },
    },
    mounted() {
        this.filteredBrandsBackup = [...this.filteredBrands];
        this.filteredMarcsBackup = [...this.filteredMarcs];
    },
};
</script>
<template>
    <div
        class="modal"
        tabindex="-1"
        role="dialog"
        id="add_articles_modal"
        :class="{ show: isVisible }"
        @click.self="closeModal"
    >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Artículo</h5>
                    <button type="button" class="close" @click="$emit('close')">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitForm" ref="articleForm">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="categories"
                                        >Seleccione el tipo de Artículo:</label
                                    >
                                    <select
                                        id="categories"
                                        v-model="selectedCategory"
                                        @change="loadBrands"
                                        class="form-control rounded-15px"
                                        required
                                    >
                                        <option value="">
                                            Seleccione una categoría
                                        </option>
                                        <option
                                            v-for="cat in category"
                                            :key="cat.id"
                                            :value="cat.id"
                                        >
                                            {{ cat.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="product_id">Marca:</label>
                                    <select
                                        id="product_id"
                                        v-model="selectedBrand"
                                        class="form-control rounded-15px"
                                        @change="loadMarcs"
                                        required
                                    >
                                        <option value="">
                                            Seleccione una marca
                                        </option>
                                        <option
                                            v-for="brand in filteredBrands"
                                            :key="brand.id"
                                            :value="brand.id"
                                        >
                                            {{ brand.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="model_id">Modelo:</label>
                                    <select
                                        id="model_id"
                                        v-model="selectedModel"
                                        class="form-control rounded-15px"
                                    >
                                        <option value="">
                                            Seleccione un modelo
                                        </option>
                                        <option
                                            v-for="model in filteredMarcs"
                                            :key="model.id"
                                            :value="model.id"
                                        >
                                            {{ model.model }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="year">Año:</label>
                                    <select
                                        id="year"
                                        v-model="selectedYear"
                                        class="form-control rounded-15px"
                                    >
                                        <option value="">
                                            Seleccione un año
                                        </option>
                                        <option
                                            v-for="y in years"
                                            :key="y.id"
                                            :value="y.id"
                                        >
                                            {{ y.year }}
                                            <!-- Asegúrate de que la propiedad se llama "year" -->
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="chasis_serial"
                                        >Chásis | Serial:</label
                                    >
                                    <input
                                        type="text"
                                        id="chasis_serial"
                                        v-model="chasis_serial"
                                        class="form-control rounded-15px"
                                        placeholder="Chasis | Serial"
                                    />
                                </div>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-sm btn-primary rounded-25px w-150px transition-3d-hover"
                        >
                            Confirmar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.modal {
    display: block;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal.show {
    display: block;
}

.modal-dialog {
    position: relative;
    margin: 1.75rem auto;
    /*max-width: 500px; */
    width: 100%;
}

.modal-content {
    background-color: #fff; /* Color de fondo del modal */
    border: 1px solid #dee2e6; /* Borde del modal */
    border-radius: 0.3rem; /* Esquinas redondeadas */
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Sombra */
    outline: 0; /* Sin borde de enfoque */
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 1rem; /* Espaciado interno */
    border-bottom: 1px solid #dee2e6; /* Línea inferior */
}

.modal-title {
    margin: 0; /* Sin margen */
    font-size: 1.25rem; /* Tamaño de fuente del título */
}

.modal-body {
    position: relative;
    padding: 1rem; /* Espaciado interno */
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 0.5rem 1rem; /* Espaciado interno */
    border-top: 1px solid #dee2e6; /* Línea superior */
}

.modal-footer .btn {
    margin-left: 0.5rem; /* Espacio entre botones */
}

.autocomplete-results {
    background-color: white;
    border: 1px solid #ccc;
    position: absolute;
    width: 100%;
    max-height: 150px;
    overflow-y: auto;
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.autocomplete-results li {
    padding: 8px;
    cursor: pointer;
}

.autocomplete-results li:hover {
    background-color: #f0f0f0;
}
</style>
