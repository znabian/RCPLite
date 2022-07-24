    
        <div class="mb-3" dir="rtl">
            <div class="card card-solid">
                <div class="card-header with-border text-center" onclick="collapse()" >
                    <h4 class="card-title" style="cursor: pointer">فیلتر پیشرفته
    
                    <div class=" pull-left">
                      <i style="font-size:initial" id="minbtn" class="fa fa-plus"></i>
                    </div>
                </h4>
                </div>
                <div class="card-body text-right" id="filter" style="display:none" id="frm">
                    
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="status">وضعیت
                                </label>
                                <select class="form-control" id="active" name="active">
                                    <option value="">همه</option>
                                    <option value="1">فعال</option>
                                    <option value="0">غیرفعال</option>
                                </select>
                            </div>
        
                            <div class="col-md-2">
                                <label for="status">
                                    <small>نام</small>
                                </label>
                                <input class="form-control" type="text" name="name" id="name" />
                            </div>
                            <div class="col-md-2">
                                <label for="status">
                                    <small>نام خانوادگی</small>
                                </label>
                                <input class="form-control" type="text" name="family"  id="family" />
                            </div>
    
                            <div class="col-md-2">
                                <label for="status">
                                    <small>تلفن تماس</small>
                                </label>
                                <input class="form-control" type="number" name="mobile"  id="mobile" />
                            </div>
    
                            <div class="col-md-2">
                                <label for="status">از تاریخ
                                </label>
                                <input class="form-control" id="trSearchDate" readonly style="background-color: white;">
                            </div>
    
                            <div class="col-md-2">
                                <label for="status">تا تاریخ
                                </label>
                                <input class="form-control" id="toTrSearchDate" readonly style="background-color: white;">
                            </div>
                                <input type="hidden" name="date" id="date">
                                <input type="hidden" name="tDate" id="tDate">
                            <div class="col-md-2">
                                <label for="status">تعداد نمایش در صفحه
                                </label>
                                <select class="form-control" id="paginate" name="paginate">
                                    <option value="">پیشفرض</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="40">40</option>
                                    <option value="60">60</option>
                                    <option value="80">80</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
    
    
    
                        </div>
                    </div>
                            <div class="col-md-2 float-left" >
                                <button type="submit" onclick="submitsearch()" class="btn btn-success">جستجو</button>
                            </div>
                    
                </div>


            </div>
        </div>