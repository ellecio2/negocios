@extends('backend.layouts.app')

@section('content')



    <div class="row">

        <div class="col-xl-10 mx-auto">

            <h6 class="fw-600">{{ translate('Home Page Settings') }}</h6>



    {{-- Home Slider --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">{{ translate('Home Slider') }}</h6>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
					{{ translate('We have limited banner height to maintain UI. We had to crop from both left & right side in view for different devices to make it responsive. Before designing banner keep these points in mind.') }}
				</div>
        <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>{{ translate('Photos, Links & Texts') }}</label>
                <div class="home-slider-target">
                    <input type="hidden" name="types[]" value="home_slider_images">
                    <input type="hidden" name="types[]" value="home_slider_links">
                    <input type="hidden" name="types[]" value="home_slider_text1">
                    <input type="hidden" name="types[]" value="home_slider_text2">
                    <input type="hidden" name="types[]" value="home_slider_text3">
                    <input type="hidden" name="types[]" value="home_slider_text4">

                    @if (get_setting('home_slider_images') != null)
                        @foreach (json_decode(get_setting('home_slider_images'), true) as $key => $value)
                            <div class="row gutters-5">
                                <!-- Existing image upload section -->
                                <div class="col-md-5">
											<div class="form-group">
												<div class="input-group" data-toggle="aizuploader" data-type="image">
					                                <div class="input-group-prepend">
					                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
					                                </div>
					                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
													<input type="hidden" name="types[]" value="home_slider_images">
					                                <input type="hidden" name="home_slider_images[]" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
					                            </div>
					                            <div class="file-preview box sm">
					                            </div>
				                            </div>
										</div>
										<div class="col-md">
											<div class="form-group">
												<input type="hidden" name="types[]" value="home_slider_links">
												<input type="text" class="form-control" placeholder="http://" name="home_slider_links[]" value="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
											</div>
										</div>

                                <!-- New text fields -->
                                <div class="col-md-12 mt-2">
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="home_slider_text1">
                                        <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 1') }}"
                                            name="home_slider_text1[]" 
                                            value="{{ json_decode(get_setting('home_slider_text1'), true)[$key] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="home_slider_text2">
                                        <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 2') }}"
                                            name="home_slider_text2[]"
                                            value="{{ json_decode(get_setting('home_slider_text2'), true)[$key] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="home_slider_text3">
                                        <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 3') }}"
                                            name="home_slider_text3[]"
                                            value="{{ json_decode(get_setting('home_slider_text3'), true)[$key] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="home_slider_text4">
                                        <input type="text" class="form-control" placeholder="{{ translate('Text 4') }}"
                                            name="home_slider_text4[]"
                                            value="{{ json_decode(get_setting('home_slider_text4'), true)[$key] ?? '' }}">
                                    </div>
                                </div>

                                <!-- Existing remove button -->
                                <div class="col-md-auto">
                                    <!-- ...existing remove button code... -->
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Modify the add new button data-content to include text fields -->
                <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more" 
                    data-content='
                    <div class="row gutters-5">
                        <!-- ...existing image and link fields... -->
                        
                        <!-- Add text fields -->
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_slider_text1">
                                <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 1') }}" name="home_slider_text1[]">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_slider_text2">
                                <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 2') }}" name="home_slider_text2[]">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_slider_text3">
                                <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 3') }}" name="home_slider_text3[]">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_slider_text4">
                                <input type="text" class="form-control" placeholder="{{ translate('Text 4') }}" name="home_slider_text4[]">
                            </div>
                        </div>
                    </div>'
                    data-target=".home-slider-target">
                    {{ translate('Add New') }}
                </button>
            </div>
            <!-- ...existing submit button... -->
        </form>
    </div>
</div>



            {{-- Today's deal --}}

            <div class="card">

                <div class="card-header">

                    <h6 class="mb-0">{{ translate("Today's deal") }}</h6>

                </div>

                <div class="card-body">

                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="form-group">

                            <label class="col-from-label">{{ translate('Large Banner') }}</label>

                            <div class="input-group " data-toggle="aizuploader" data-type="image">

                                <div class="input-group-prepend">

                                    <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>

                                </div>

                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                <input type="hidden" name="types[]" value="todays_deal_banner">

                                <input type="hidden" name="todays_deal_banner"
                                    value="{{ get_setting('todays_deal_banner') }}" class="selected-files">

                            </div>

                            <div class="file-preview box"></div>

                        </div>

                        <div class="form-group">

                            <label class="col-from-label">{{ translate('Small Banner') }}</label>

                            <div class="input-group " data-toggle="aizuploader" data-type="image">

                                <div class="input-group-prepend">

                                    <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>

                                </div>

                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                <input type="hidden" name="types[]" value="todays_deal_banner_small">

                                <input type="hidden" name="todays_deal_banner_small"
                                    value="{{ get_setting('todays_deal_banner_small') }}" class="selected-files">

                            </div>

                            <div class="file-preview box"></div>

                        </div>

                        <div class="form-group">

                            <label class="col-from-label">{{ translate('Products background color') }}</label>

                            <input type="hidden" name="types[]" value="todays_deal_bg_color">

                            <input type="text" class="form-control" placeholder="#3d4666" name="todays_deal_bg_color"
                                value="{{ get_setting('todays_deal_bg_color') }}">

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>



            {{-- Home Banner 1 --}}

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Banner & Links & Texts') }}</label>
                            <div class="home-banner1-target">
                                <input type="hidden" name="types[]" value="home_banner1_images">
                                <input type="hidden" name="types[]" value="home_banner1_links">
                                <input type="hidden" name="types[]" value="home_banner1_text1">
                                <input type="hidden" name="types[]" value="home_banner1_text2">
                                <input type="hidden" name="types[]" value="home_banner1_text3">

                                @if (get_setting('home_banner1_images') != null)
                                    @foreach (json_decode(get_setting('home_banner1_images'), true) as $key => $value)
                                        <div class="row gutters-5">

                                            <div class="col-md-5">

                                                <div class="form-group">

                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">

                                                        <div class="input-group-prepend">

                                                            <div
                                                                class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse') }}</div>

                                                        </div>

                                                        <div class="form-control file-amount">
                                                            {{ translate('Choose File') }}</div>

                                                        <input type="hidden" name="types[]" value="home_banner1_images">

                                                        <input type="hidden" name="home_banner1_images[]"
                                                            class="selected-files"
                                                            value="{{ json_decode(get_setting('home_banner1_images'), true)[$key] }}">

                                                    </div>

                                                    <div class="file-preview box sm">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md">

                                                <div class="form-group">

                                                    <input type="hidden" name="types[]" value="home_banner1_links">

                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="home_banner1_links[]"
                                                        value="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}">

                                                </div>

                                            </div>

                                            <!-- New text fields -->
                                            <div class="col-md-12 mt-2">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner1_text1">
                                                    <input type="text" class="form-control mb-2"
                                                        placeholder="{{ translate('Text 1') }}"
                                                        name="home_banner1_text1[]"
                                                        value="{{ json_decode(get_setting('home_banner1_text1'), true)[$key] ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner1_text2">
                                                    <input type="text" class="form-control mb-2"
                                                        placeholder="{{ translate('Text 2') }}"
                                                        name="home_banner1_text2[]"
                                                        value="{{ json_decode(get_setting('home_banner1_text2'), true)[$key] ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner1_text3">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('Text 3') }}"
                                                        name="home_banner1_text3[]"
                                                        value="{{ json_decode(get_setting('home_banner1_text3'), true)[$key] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">

                                                <div class="form-group">

                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">

                                                        <i class="las la-times"></i>

                                                    </button>

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach
                                @endif

                            </div>


                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='

							<div class="row gutters-5">

								<div class="col-md-5">

									<div class="form-group">

										<div class="input-group" data-toggle="aizuploader" data-type="image">

											<div class="input-group-prepend">

												<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>

											</div>

											<div class="form-control file-amount">{{ translate('Choose File') }}</div>

											<input type="hidden" name="types[]" value="home_banner1_images">

											<input type="hidden" name="home_banner1_images[]" class="selected-files">

										</div>

										<div class="file-preview box sm">

										</div>

									</div>

								</div>

								<div class="col-md">

									<div class="form-group">

										<input type="hidden" name="types[]" value="home_banner1_links">

										<input type="text" class="form-control" placeholder="http://" name="home_banner1_links[]">

									</div>

								</div>

                                      <!-- Add text fields -->
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_banner1_text1">
                                <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 1') }}" name="home_banner1_text1[]">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_banner1_text2">
                                <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 2') }}" name="home_banner1_text2[]">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_banner1_text3">
                                <input type="text" class="form-control" placeholder="{{ translate('Text 3') }}" name="home_banner1_text3[]">
                            </div>
                        </div>

								<div class="col-md-auto">

									<div class="form-group">

										<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">

											<i class="las la-times"></i>

										</button>

									</div>

								</div>

							</div>'
                                data-target=".home-banner1-target">

                                {{ translate('Add New') }}

                            </button>

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>

            {{-- Home Banner 2 --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Home Banner 2 (Max 3)') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Banner & Links & Texts') }}</label>
                            <div class="home-banner2-target">
                                <input type="hidden" name="types[]" value="home_banner2_images">
                                <input type="hidden" name="types[]" value="home_banner2_links">
                                <input type="hidden" name="types[]" value="home_banner2_text1">
                                <input type="hidden" name="types[]" value="home_banner2_text2">

                                @if (get_setting('home_banner2_images') != null)
                                    @foreach (json_decode(get_setting('home_banner2_images'), true) as $key => $value)
                                        <div class="row gutters-5">

                                            <div class="col-md-5">

                                                <div class="form-group">

                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">

                                                        <div class="input-group-prepend">

                                                            <div
                                                                class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse') }}</div>

                                                        </div>

                                                        <div class="form-control file-amount">
                                                            {{ translate('Choose File') }}</div>

                                                        <input type="hidden" name="types[]" value="home_banner2_images">

                                                        <input type="hidden" name="home_banner2_images[]"
                                                            class="selected-files"
                                                            value="{{ json_decode(get_setting('home_banner2_images'), true)[$key] }}">

                                                    </div>

                                                    <div class="file-preview box sm">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md">

                                                <div class="form-group">

                                                    <input type="hidden" name="types[]" value="home_banner2_links">

                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="home_banner2_links[]"
                                                        value="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}">

                                                </div>

                                            </div>

                                            <div class="col-md-12 mt-2">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner2_text1">
                                                    <input type="text" class="form-control mb-2"
                                                        placeholder="{{ translate('Text 1') }}"
                                                        name="home_banner2_text1[]"
                                                        value="{{ json_decode(get_setting('home_banner2_text1'), true)[$key] ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner2_text2">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('Text 2') }}"
                                                        name="home_banner2_text2[]"
                                                        value="{{ json_decode(get_setting('home_banner2_text2'), true)[$key] ?? '' }}">
                                                </div>
                                            </div>


                                            <div class="col-md-auto">

                                                <div class="form-group">

                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">

                                                        <i class="las la-times"></i>

                                                    </button>

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='

							<div class="row gutters-5">

								<div class="col-md-5">

									<div class="form-group">

										<div class="input-group" data-toggle="aizuploader" data-type="image">

											<div class="input-group-prepend">

												<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>

											</div>

											<div class="form-control file-amount">{{ translate('Choose File') }}</div>

											<input type="hidden" name="types[]" value="home_banner2_images">

											<input type="hidden" name="home_banner2_images[]" class="selected-files">

										</div>

										<div class="file-preview box sm">

										</div>

									</div>

								</div>

								<div class="col-md">

									<div class="form-group">

										<input type="hidden" name="types[]" value="home_banner2_links">

										<input type="text" class="form-control" placeholder="http://" name="home_banner2_links[]">

									</div>

								</div>

                                  <!-- Add text fields -->
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_banner2_text1">
                                <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 1') }}" name="home_banner2_text1[]">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="home_banner2_text2">
                                <input type="text" class="form-control" placeholder="{{ translate('Text 2') }}" name="home_banner2_text2[]">
                            </div>
                        </div>

								<div class="col-md-auto">

									<div class="form-group">

										<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">

											<i class="las la-times"></i>

										</button>

									</div>

								</div>

							</div>'
                                data-target=".home-banner2-target">

                                {{ translate('Add New') }}

                            </button>

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>

            {{-- Home Banner 3 --}}

            <div class="card">

                <div class="card-header">

                    <h6 class="mb-0">{{ translate('Home Banner 3 (Max 3)') }}</h6>

                </div>

                <div class="card-body">

                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="form-group">

                            <label>{{ translate('Banner & Links') }}</label>

                            <div class="home-banner3-target">

                                <input type="hidden" name="types[]" value="home_banner3_images">

                                <input type="hidden" name="types[]" value="home_banner3_links">

                                @if (get_setting('home_banner3_images') != null)
                                    @foreach (json_decode(get_setting('home_banner3_images'), true) as $key => $value)
                                        <div class="row gutters-5">

                                            <div class="col-md-5">

                                                <div class="form-group">

                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">

                                                        <div class="input-group-prepend">

                                                            <div
                                                                class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse') }}</div>

                                                        </div>

                                                        <div class="form-control file-amount">
                                                            {{ translate('Choose File') }}</div>

                                                        <input type="hidden" name="types[]" value="home_banner3_images">

                                                        <input type="hidden" name="home_banner3_images[]"
                                                            class="selected-files"
                                                            value="{{ json_decode(get_setting('home_banner3_images'), true)[$key] }}">

                                                    </div>

                                                    <div class="file-preview box sm">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md">

                                                <div class="form-group">

                                                    <input type="hidden" name="types[]" value="home_banner3_links">

                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="home_banner3_links[]"
                                                        value="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}">

                                                </div>

                                            </div>

                                            <div class="col-md-auto">

                                                <div class="form-group">

                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">

                                                        <i class="las la-times"></i>

                                                    </button>

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='

							<div class="row gutters-5">

								<div class="col-md-5">

									<div class="form-group">

										<div class="input-group" data-toggle="aizuploader" data-type="image">

											<div class="input-group-prepend">

												<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>

											</div>

											<div class="form-control file-amount">{{ translate('Choose File') }}</div>

											<input type="hidden" name="types[]" value="home_banner3_images">

											<input type="hidden" name="home_banner3_images[]" class="selected-files">

										</div>

										<div class="file-preview box sm">

										</div>

									</div>

								</div>

								<div class="col-md">

									<div class="form-group">

										<input type="hidden" name="types[]" value="home_banner3_links">

										<input type="text" class="form-control" placeholder="http://" name="home_banner3_links[]">

									</div>

								</div>

								<div class="col-md-auto">

									<div class="form-group">

										<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">

											<i class="las la-times"></i>

										</button>

									</div>

								</div>

							</div>'
                                data-target=".home-banner3-target">

                                {{ translate('Add New') }}

                            </button>

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>

            {{-- Home Banner 4 --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Home Banner 4 (Max 2)') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Banner & Links & Texts') }}</label>
                            <div class="home-banner4-target">
                                <input type="hidden" name="types[]" value="home_banner4_images">
                                <input type="hidden" name="types[]" value="home_banner4_links">
                                <input type="hidden" name="types[]" value="home_banner4_text1">
                                <input type="hidden" name="types[]" value="home_banner4_text2">
                                <input type="hidden" name="types[]" value="home_banner4_text3">

                                @if (get_setting('home_banner4_images') != null)
                                    @foreach (json_decode(get_setting('home_banner4_images'), true) as $key => $value)
                                        <div class="row gutters-5">

                                            <div class="col-md-5">

                                                <div class="form-group">

                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">

                                                        <div class="input-group-prepend">

                                                            <div
                                                                class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse') }}</div>

                                                        </div>

                                                        <div class="form-control file-amount">
                                                            {{ translate('Choose File') }}</div>

                                                        <input type="hidden" name="types[]" value="home_banner4_images">

                                                        <input type="hidden" name="home_banner4_images[]"
                                                            class="selected-files"
                                                            value="{{ json_decode(get_setting('home_banner4_images'), true)[$key] }}">

                                                    </div>

                                                    <div class="file-preview box sm">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md">

                                                <div class="form-group">

                                                    <input type="hidden" name="types[]" value="home_banner4_links">

                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="home_banner4_links[]"
                                                        value="{{ json_decode(get_setting('home_banner4_links'), true)[$key] }}">

                                                </div>

                                            </div>

                                            <!-- Add text fields -->
                                            <div class="col-md-12 mt-2">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner4_text1">
                                                    <input type="text" class="form-control mb-2"
                                                        placeholder="{{ translate('Text 1') }}"
                                                        name="home_banner4_text1[]"
                                                        value="{{ json_decode(get_setting('home_banner4_text1'), true)[$key] ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner4_text2">
                                                    <input type="text" class="form-control mb-2"
                                                        placeholder="{{ translate('Text 2') }}"
                                                        name="home_banner4_text2[]"
                                                        value="{{ json_decode(get_setting('home_banner4_text2'), true)[$key] ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner4_text3">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('Text 3') }}"
                                                        name="home_banner4_text3[]"
                                                        value="{{ json_decode(get_setting('home_banner4_text3'), true)[$key] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">

                                                <div class="form-group">

                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">

                                                        <i class="las la-times"></i>

                                                    </button>

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='
	
								<div class="row gutters-5">
	
									<div class="col-md-5">
	
										<div class="form-group">
	
											<div class="input-group" data-toggle="aizuploader" data-type="image">
	
												<div class="input-group-prepend">
	
													<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
	
												</div>
	
												<div class="form-control file-amount">{{ translate('Choose File') }}</div>
	
												<input type="hidden" name="types[]" value="home_banner4_images">
	
												<input type="hidden" name="home_banner4_images[]" class="selected-files">
	
											</div>
	
											<div class="file-preview box sm">
	
											</div>
	
										</div>
	
									</div>
	
									<div class="col-md">
	
										<div class="form-group">
	
											<input type="hidden" name="types[]" value="home_banner4_links">
	
											<input type="text" class="form-control" placeholder="http://" name="home_banner4_links[]">
	
										</div>
	
									</div>

                                       <!-- Add text fields -->
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="home_banner4_text1">
                            <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 1') }}" name="home_banner4_text1[]">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="home_banner4_text2">
                            <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 2') }}" name="home_banner4_text2[]">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="home_banner4_text3">
                            <input type="text" class="form-control" placeholder="{{ translate('Text 3') }}" name="home_banner4_text3[]">
                        </div>
                    </div>
	
									<div class="col-md-auto">
	
										<div class="form-group">
	
											<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
	
												<i class="las la-times"></i>
	
											</button>
	
										</div>
	
									</div>
	
								</div>'
                                data-target=".home-banner4-target">

                                {{ translate('Add New') }}

                            </button>

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Home Banner 5 --}}

            {{-- Home Banner 5 section --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Home Banner 5 (Max 1)') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Banner & Links & Texts') }}</label>
                            <div class="home-banner5-target">
                                <input type="hidden" name="types[]" value="home_banner5_images">
                                <input type="hidden" name="types[]" value="home_banner5_links">
                                <input type="hidden" name="types[]" value="home_banner5_text1">
                                <input type="hidden" name="types[]" value="home_banner5_text2">
                                <input type="hidden" name="types[]" value="home_banner5_text3">

                                @if (get_setting('home_banner5_images') != null)
                                    @foreach (json_decode(get_setting('home_banner5_images'), true) as $key => $value)
                                        <div class="row gutters-5">

                                            <div class="col-md-5">

                                                <div class="form-group">

                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">

                                                        <div class="input-group-prepend">

                                                            <div
                                                                class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse') }}</div>

                                                        </div>

                                                        <div class="form-control file-amount">
                                                            {{ translate('Choose File') }}</div>

                                                        <input type="hidden" name="types[]" value="home_banner5_images">

                                                        <input type="hidden" name="home_banner5_images[]"
                                                            class="selected-files"
                                                            value="{{ json_decode(get_setting('home_banner5_images'), true)[$key] }}">

                                                    </div>

                                                    <div class="file-preview box sm">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md">

                                                <div class="form-group">

                                                    <input type="hidden" name="types[]" value="home_banner5_links">

                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="home_banner5_links[]"
                                                        value="{{ json_decode(get_setting('home_banner5_links'), true)[$key] }}">

                                                </div>

                                            </div>

                                            <div class="col-md-12 mt-2">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner5_text1">
                                                    <input type="text" class="form-control mb-2"
                                                        placeholder="{{ translate('Text 1') }}"
                                                        name="home_banner5_text1[]"
                                                        value="{{ json_decode(get_setting('home_banner5_text1'), true)[$key] ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner5_text2">
                                                    <input type="text" class="form-control mb-2"
                                                        placeholder="{{ translate('Text 2') }}"
                                                        name="home_banner5_text2[]"
                                                        value="{{ json_decode(get_setting('home_banner5_text2'), true)[$key] ?? '' }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner5_text3">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('Text 3') }}"
                                                        name="home_banner5_text3[]"
                                                        value="{{ json_decode(get_setting('home_banner5_text3'), true)[$key] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">

                                                <div class="form-group">

                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">

                                                        <i class="las la-times"></i>

                                                    </button>

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='
	
								<div class="row gutters-5">
	
									<div class="col-md-5">
	
										<div class="form-group">
	
											<div class="input-group" data-toggle="aizuploader" data-type="image">
	
												<div class="input-group-prepend">
	
													<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
	
												</div>
	
												<div class="form-control file-amount">{{ translate('Choose File') }}</div>
	
												<input type="hidden" name="types[]" value="home_banner5_images">
	
												<input type="hidden" name="home_banner5_images[]" class="selected-files">
	
											</div>
	
											<div class="file-preview box sm">
	
											</div>
	
										</div>
	
									</div>
	
									<div class="col-md">
	
										<div class="form-group">
	
											<input type="hidden" name="types[]" value="home_banner5_links">
	
											<input type="text" class="form-control" placeholder="http://" name="home_banner5_links[]">
	
										</div>
	
									</div>

                                     <!-- Add text fields -->
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="home_banner5_text1">
                            <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 1') }}" name="home_banner5_text1[]">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="home_banner5_text2">
                            <input type="text" class="form-control mb-2" placeholder="{{ translate('Text 2') }}" name="home_banner5_text2[]">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="home_banner5_text3">
                            <input type="text" class="form-control" placeholder="{{ translate('Text 3') }}" name="home_banner5_text3[]">
                        </div>
                    </div>
	
									<div class="col-md-auto">
	
										<div class="form-group">
	
											<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
	
												<i class="las la-times"></i>
	
											</button>
	
										</div>
	
									</div>
	
								</div>'
                                data-target=".home-banner5-target">

                                {{ translate('Add New') }}

                            </button>

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>

            {{-- Home Banner 5 --}}

            {{-- Auction Banner --}}

            @if (addon_is_activated('auction'))
                <div class="card">

                    <div class="card-header">

                        <h6 class="mb-0">{{ translate('Auction Banner') }}</h6>

                    </div>

                    <div class="card-body">

                        <form action="{{ route('business_settings.update') }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf



                            <div class="form-group">

                                <div class="input-group" data-toggle="aizuploader" data-type="image">

                                    <div class="input-group-prepend">

                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>

                                    </div>

                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                    <input type="hidden" name="types[]" value="auction_banner_image">

                                    <input type="hidden" name="auction_banner_image" class="selected-files"
                                        value="{{ get_setting('auction_banner_image') }}">

                                </div>

                                <div class="file-preview box sm">

                                </div>

                            </div>

                            <div class="text-right">

                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                            </div>

                        </form>

                    </div>

                </div>
            @endif



            {{-- Coupon system --}}

            @if (get_setting('coupon_system') == 1)
                <div class="card">

                    <div class="card-header">

                        <h6 class="mb-0">{{ translate('Coupon Section') }}</h6>

                    </div>

                    <div class="card-body">

                        <form action="{{ route('business_settings.update') }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="form-group">

                                <label>{{ translate('Background Color') }}</label>

                                <input type="hidden" name="types[]" value="cupon_background_color">

                                <input type="text" class="form-control" placeholder="#000"
                                    name="cupon_background_color" value="{{ get_setting('cupon_background_color') }}">

                            </div>

                            <div class="form-group">

                                <label>{{ translate('Title') }}</label>

                                <input type="hidden" name="types[]" value="cupon_title">

                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}"
                                    name="cupon_title" value="{{ get_setting('cupon_title') }}">

                            </div>

                            <div class="form-group">

                                <label>{{ translate('Subtitle') }}</label>

                                <input type="hidden" name="types[]" value="cupon_subtitle">

                                <input type="text" class="form-control" placeholder="{{ translate('Subtitle') }}"
                                    name="cupon_subtitle" value="{{ get_setting('cupon_subtitle') }}">

                            </div>

                            <div class="text-right">

                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                            </div>

                        </form>

                    </div>

                </div>
            @endif



            {{-- Home categories --}}

            <div class="card">

                <div class="card-header">

                    <h6 class="mb-0">{{ translate('Home Categories') }}</h6>

                </div>

                <div class="card-body">

                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="form-group">

                            <label>{{ translate('Categories') }}</label>

                            <div class="home-categories-target">

                                <input type="hidden" name="types[]" value="home_categories">

                                @if (get_setting('home_categories') != null)
                                    @foreach (json_decode(get_setting('home_categories'), true) as $key => $value)
                                        <div class="row gutters-5">

                                            <div class="col">

                                                <div class="form-group">

                                                    <select class="form-control aiz-selectpicker" name="home_categories[]"
                                                        data-live-search="true" data-selected={{ $value }}
                                                        required>

                                                        @foreach (\App\Models\Category::where('parent_id', 0)->with('childrenCategories')->get() as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->getTranslation('name') }}</option>

                                                            @foreach ($category->childrenCategories as $childCategory)
                                                                @include('categories.child_category', [
                                                                    'child_category' => $childCategory,
                                                                ])
                                                            @endforeach
                                                        @endforeach

                                                    </select>

                                                </div>

                                            </div>

                                            <div class="col-auto">

                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">

                                                    <i class="las la-times"></i>

                                                </button>

                                            </div>

                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='<div class="row gutters-5">

								<div class="col">

									<div class="form-group">

										<select class="form-control aiz-selectpicker" name="home_categories[]" data-live-search="true" required>

											@foreach (\App\Models\Category::all() as $key => $category)
<option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
@endforeach

										</select>

									</div>

								</div>

								<div class="col-auto">

									<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">

										<i class="las la-times"></i>

									</button>

								</div>

							</div>'
                                data-target=".home-categories-target">

                                {{ translate('Add New') }}

                            </button>

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>



            {{-- Classified Banner --}}

            <div class="card">

                <div class="card-header">

                    <h6 class="mb-0">{{ translate('Classified Ads Banner') }}</h6>

                </div>

                <div class="card-body">

                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="form-group">

                            <label>{{ translate('Large Banner') }}</label>

                            <div class="input-group " data-toggle="aizuploader" data-type="image">

                                <div class="input-group-prepend">

                                    <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>

                                </div>

                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                <input type="hidden" name="types[]" value="classified_banner_image">

                                <input type="hidden" name="classified_banner_image"
                                    value="{{ get_setting('classified_banner_image') }}" class="selected-files">

                            </div>

                            <div class="file-preview box"></div>

                        </div>

                        <div class="form-group">

                            <label>{{ translate('Small Banner') }}</label>

                            <div class="input-group " data-toggle="aizuploader" data-type="image">

                                <div class="input-group-prepend">

                                    <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>

                                </div>

                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                <input type="hidden" name="types[]" value="classified_banner_image_small">

                                <input type="hidden" name="classified_banner_image_small"
                                    value="{{ get_setting('classified_banner_image_small') }}" class="selected-files">

                            </div>

                            <div class="file-preview box"></div>

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>



            {{-- Top 10 --}}

            <div class="card">

                <div class="card-header">

                    <h6 class="mb-0">{{ translate('Top 12') }}</h6>

                </div>

                <div class="card-body">

                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        {{-- <div class="form-group row">

						<label class="col-md-2 col-from-label">{{translate('Top Categories (Max 10)')}}</label>

						<div class="col-md-10">

							<input type="hidden" name="types[]" value="top10_categories">

							<select name="top10_categories[]" class="form-control aiz-selectpicker" multiple data-max-options="10" data-live-search="true" data-selected="{{ get_setting('top10_categories') }}">

								@foreach (\App\Models\Category::where('parent_id', 0)->with('childrenCategories')->get() as $category)

									<option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>

									@foreach ($category->childrenCategories as $childCategory)

										@include('categories.child_category', ['child_category' => $childCategory])

									@endforeach

								@endforeach

							</select>

						</div>

					</div>

					<div class="form-group row">

						<label class="col-md-2 col-from-label">{{translate('Top Brands (Max 10)')}}</label>

						<div class="col-md-10">

							<input type="hidden" name="types[]" value="top10_brands">

							<select name="top10_brands[]" class="form-control aiz-selectpicker" multiple data-max-options="10" data-live-search="true" data-selected="{{ get_setting('top10_brands') }}">

								@foreach (\App\Models\Brand::all() as $key => $brand)

									<option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}</option>

								@endforeach

							</select>

						</div>

					</div> --}}

                        <div class="form-group row">

                            <label class="col-md-2 col-from-label">{{ translate('Top Brands (Max 12)') }}</label>

                            <div class="col-md-10">

                                <input type="hidden" name="types[]" value="top_brands">

                                <select name="top_brands[]" class="form-control aiz-selectpicker" multiple
                                    data-max-options="12" data-live-search="true"
                                    data-selected="{{ get_setting('top_brands') }}">

                                    @foreach (\App\Models\Brand::all() as $key => $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div class="text-right">

                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>



@endsection



@section('script')
    <script type="text/javascript">
        $(document).ready(function() {

            AIZ.plugins.bootstrapSelect('refresh');

        });
    </script>
@endsection
