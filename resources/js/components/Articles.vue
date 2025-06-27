<template>
    <div class="row gutters-16">
        <div class="col-12">
            <div class="aiz-titlebar mb-4">
                <div class="align-items-center">
                    <div class="col-12">
                        <h1 class="fs-20 fw-700 text-dark">Mis Artículos</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12" style="justify-content: center">
            <div class="mb-2">
                <div
                    v-if="isAddonActivated"
                    class="col-sm-6 col-md-3 mx-auto mb-4 center"
                >
                    <div
                        class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-33 hov-bg-soft-light rounded-15p"
                        @click="showAddArticlesModal"
                    >
                        <span
                            class="size-60px rounded-circle mx-auto bg-dark d-flex align-items-center justify-content-center mb-3"
                        >
                            <i class="las la-plus la-3x text-white"></i>
                        </span>
                        <div class="fs-14 fw-600 text-dark">Artículos</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card rounded-0 shadow-none border rounded-15p">
                <div class="card-header border-bottom-0">
                    <h5
                        class="mb-0 fs-20 fw-700 text-dark text-center text-md-left"
                    >
                        Lista de Artículos Registrados
                    </h5>
                </div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm">
                        <thead class="fs-14 th-table">
                            <tr class="text-center">
                                <th>Tipos de Artículos</th>
                                <th>Producto</th>
                                <th>Modelo</th>
                                <th>Año</th>
                                <th>Chasis / SN</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray fs-13">
                            <tr
                                v-for="article in articles"
                                :key="article.id"
                                class="text-center"
                            >
                                <td>
                                    <div class="py-2 fw-700">
                                        {{ article.category_name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="py-2 fw-700">
                                        {{ article.product_name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="py-2 fw-700">
                                        {{ article.model_name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="py-2 fw-700">
                                        {{ article.year_name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="py-2 fw-700">
                                        {{ article.chasis_serial }}
                                    </div>
                                </td>
                                <td>
                                    <a
                                        @click="deleteArticle(article.id)"
                                        class="py-2 px-2 d-inline-block fw-700 text-negro header_menu_links hov-bg-black-10 rounded-15p"
                                        style="cursor: pointer"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="1em"
                                            height="1em"
                                            viewBox="0 0 14 14"
                                        >
                                            <path
                                                fill="black"
                                                fill-rule="evenodd"
                                                d="M1.707.293A1 1 0 0 0 .293 1.707L5.586 7L.293 12.293a1 1 0 1 0 1.414 1.414L7 8.414l5.293 5.293a1 1 0 0 0 1.414-1.414L8.414 7l5.293-5.293A1 1 0 0 0 12.293.293L7 5.586z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <button
                            v-if="pagination.prev_page_url"
                            @click="fetchArticles(pagination.prev_page_url)"
                            class="btn btn-primary btn-xs"
                        >
                            Anterior
                        </button>

                        <span>
                            Página {{ pagination.current_page }} de
                            {{ pagination.last_page }}
                        </span>

                        <button
                            v-if="pagination.next_page_url"
                            @click="fetchArticles(pagination.next_page_url)"
                            class="btn btn-primary btn-xs"
                        >
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <ArticlesModal
            v-if="showModal"
            :category="category"
            @close="showModal = false"
            @articleAdded="fetchArticles"
            :brand="filteredBrands"
        />
    </div>
</template>

<script>
import ArticlesModal from "./ArticlesModal.vue";
import Swal from "sweetalert2";

export default {
    components: {
        ArticlesModal,
    },
    data() {
        return {
            articles: [],
            isAddonActivated: true,
            showModal: false,
            category: [],
            filteredBrands: [],
            pagination: {
                current_page: 1,
                last_page: 1,
                prev_page_url: null,
                next_page_url: null,
            },
        };
    },
    methods: {
        showAddArticlesModal() {
            console.log("Show Add Articles Modal");
            this.showModal = true;
        },
        handleArticleAdded(newArticle) {
            this.articles.push(newArticle);
            this.showModal = false;
        },
        deleteArticle(articleId) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "No podrás revertir esto una vez eliminado.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`delete/articles/${articleId}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Accept: "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.status === "success") {
                                Swal.fire("Eliminado", data.message, "success");
                                this.fetchArticles();
                            } else {
                                Swal.fire("Error", data.message, "error");
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            Swal.fire(
                                "Error",
                                "Hubo un problema con la eliminación.",
                                "error",
                            );
                        });
                }
            });
        },
        fetchArticles(url = "/load_articles2") {
            fetch(url, {
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    // Asigna los artículos
                    this.articles = data.articles.data;

                    // Asigna la información de paginación
                    this.pagination = {
                        current_page: data.articles.current_page,
                        last_page: data.articles.last_page,
                        prev_page_url: data.articles.prev_page_url,
                        next_page_url: data.articles.next_page_url,
                    };

                    // Asigna otras categorías si es necesario
                    this.category = data.category;
                })
                .catch((error) => {
                    console.error("Error fetching data:", error);
                });
        },
    },
    async mounted() {
        await this.fetchArticles();
    },
};
</script>

<style scoped>
.menu_article {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .pagination button {
        padding: 6px 10px;
        font-size: 12px;
    }

    .pagination span {
        font-size: 14px;
    }
}
</style>
