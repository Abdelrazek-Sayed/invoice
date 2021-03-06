@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    اضافة فاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    اضافة فاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @include('includes.alerts')
    <!-- row -->
    <div class="row">

        <div class="col-lg-12 col-md-12">
            @if (count($sections) > 0)
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            <div class="row">
                                <div class="col">
                                    <label for="inputName" class="control-label">رقم الفاتورة</label>
                                    <input type="text" class="form-control" id="inputName" name="invoice_number"
                                        title="يرجي ادخال رقم الفاتورة" value="{{ old('invoice_number') }}" required>
                                    @error('invoice_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label>تاريخ الفاتورة</label>
                                    <input class="form-control fc-datepicker" name="invoice_date" placeholder="YYYY-MM-DD"
                                        type="text" value="{{ date('Y-m-d') }}" required>
                                    @error('invoice_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label>تاريخ الاستحقاق</label>
                                    <input class="form-control fc-datepicker" name="due_date" placeholder="YYYY-MM-DD"
                                        type="text" value="{{ old('due_date') }}" autocomplete="off" required>
                                    @error('due_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            {{-- 2 --}}
                            <div class="row">
                                <div class="col">
                                    <label for="inputName" class="control-label">القسم</label>
                                    <select name="section_id" class="form-control SlectBox"
                                        onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                        <!--placeholder-->
                                        <option value="" selected disabled>حدد القسم</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">المنتج</label>
                                    <select id="product" name="product" class="form-control">

                                    </select>

                                    @error('product')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">مبلغ التحصيل</label>
                                    <input type="text" class="form-control" id="inputName" name="amount_collection"
                                        value="{{ old('amount_collection') }}"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    @error('amount_collection')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            {{-- 3 --}}

                            <div class="row">

                                <div class="col">
                                    <label for="inputName" class="control-label">مبلغ العمولة</label>
                                    <input type="text" class="form-control form-control-lg" id="Amount_Commission"
                                        value="{{ old('amount_commission') }}" name="amount_commission"
                                        title="يرجي ادخال مبلغ العمولة "
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        required>
                                    @error('amount_commission')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">الخصم</label>
                                    <input type="text" class="form-control form-control-lg" id="Discount" name="discount"
                                        title="يرجي ادخال مبلغ الخصم " value=0 value="{{ old('discount') }}"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        required>

                                    @error('discount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                    <select name="rate_vat" id="Rate_Vat" class="form-control" onchange="myFunction()">
                                        <!--placeholder-->
                                        <option value="" selected disabled>حدد نسبة الضريبة</option>
                                        <option value=" 5%">5%</option>
                                        <option value="10%">10%</option>
                                    </select>
                                    @error('rate_vat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            {{-- 4 --}}

                            <div class="row">
                                <div class="col">
                                    <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                    <input type="text" class="form-control" id="Value_Vat" name="value_vat"
                                        value="{{ old('value_vat') }}" readonly>
                                    @error('value_vat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                    <input type="text" class="form-control" id="Total" name="total"
                                        value="{{ old('total') }}" readonly>
                                    @error('total')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 5 --}}
                            <div class="row">
                                <div class="col">
                                    <label for="exampleTextarea">ملاحظات</label>
                                    <textarea class="form-control" id="exampleTextarea" name="note"
                                        rows="3">{{ old('note') }}</textarea>
                                </div>
                                @error('note')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div><br>

                            <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                            <h5 class="card-title">المرفقات</h5>

                            <div class="col-sm-12 col-md-12">
                                <input type="file" name="file" class="dropify"
                                    accept=".pdf,.jpg, .png, image/jpeg, image/png" data-height="70" />
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div><br>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                            </div>


                        </form>
                    </div>
                </div>
            @else
                <p>يرجى اضافة قسم ومنتج اولا</p>
            @endif
        </div>
    </div>

    </div>

    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();

    </script>

    <script>
        $(document).ready(function() {
            $('select[name="section_id"]').on('change', function() {
                var section_id = $(this).val();
                if (section_id) {
                    $.ajax({
                        url: "{{ url('invoice/section/getproducts') }}/" + section_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="product"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="product"]').append('<option value="' +
                                    value + '">' + value + '</option>');
                            });
                        },


                    });

                } else {

                    alert('AJAX load did not work');
                }
            });

        });

    </script>

    <script>
        function myFunction() {

            var Amount_Commission = parseFloat(document.getElementById("Amount_Commission").value);
            var Discount = parseFloat(document.getElementById("Discount").value);
            var Rate_VAT = parseFloat(document.getElementById("Rate_Vat").value);
            var Value_VAT = parseFloat(document.getElementById("Value_Vat").value);

            var Amount_Commission2 = Amount_Commission - Discount;


            if (typeof Amount_Commission === 'undefined' || !Amount_Commission) {

                alert('يرجي ادخال مبلغ العمولة ');

            } else {
                var intResults = Amount_Commission2 * Rate_VAT / 100;

                var intResults2 = parseFloat(intResults + Amount_Commission2);

                sumq = parseFloat(intResults).toFixed(2);

                sumt = parseFloat(intResults2).toFixed(2);

                document.getElementById("Value_Vat").value = sumq;

                document.getElementById("Total").value = sumt;

            }

        }

    </script>


@endsection


{{-- success: function(data) {
    var d = $('select[name="product"]').empty();
    $.each(data, function(key, value) {

        $('select[name="product"]').append(
            '<option value="' + value.id + '">' + value
            .name + '</option>');
    });
}, --}}
