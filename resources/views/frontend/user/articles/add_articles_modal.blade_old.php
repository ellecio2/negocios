<div class="modal fade" id="add_articles_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Artículo</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>

            </div>

            <div>
                <form id="articleForm" action="{{ route('articles.store') }}" method="POST">
                    @csrf
                    <div class="card manual-payment-card rounded-0 shadow-none border p-4">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Seleccione el tipo de Artículo:</label>
                                    <select class="form-control aiz-selectpicker rounded-15px"
                                            data-placeholder="Seleccione el tipo:" id="categories"
                                            name="category_id" data-live-search="true" required
                                            style="border-radius: 15px !important;">
                                        <option value="Seleccione el tipo:" selected>Seleccione el tipo:
                                        </option>
                                        @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Marca:</label>
                                    <select class="form-control rounded-15px"
                                            id="product_id" name="product_id"
                                            required>
                                        <option value="">Seleccione una modelo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Modelo:</label>
                                    <select class="form-control rounded-15px"
                                            id="model_id" name="model_id"
                                            required>
                                        <option value="">Seleccione modelo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Año:</label>
                                    <select class="form-control rounded-15px"
                                            id="year" name="year"
                                            required>
                                        <option value="">Seleccione año</option>
                                        @foreach ($year as $key => $y)
                                            <option value="{{ $y->id }}">{{ $y->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">Chásis | Serial:</label>
                                    <input type="text" lang="en" class="form-control rounded-15px" min="0"
                                           step="0.01" name="chasis_serial" placeholder="Chásis | Serial">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right mt-3 mr-4">
                        <button type="submit"
                                class="btn btn-sm btn-primary rounded-25px w-150px transition-3d-hover">
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

