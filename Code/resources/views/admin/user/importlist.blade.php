@extends('layouts.masterLayout')
@section('title', $pageTitle)
@section('pagestyles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"/>
<style>
   table.dataTable tbody td {
      padding: 15px 10px;
   }
</style>
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="card">
      <div class="card-datatable table-responsive">
         <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <form method="post" action="{{ $formURL }}">
               @csrf
               <div class="card-header flex-column flex-md-row">
                  <div class="head-label">
                     <h5 class="card-title mb-0">{{$pageTitle}}</h5>
                  </div>
                  <div class="dt-action-buttons text-end pt-3 pt-md-0">
                     <div class="dt-buttons"> 
                        <a class="closeBtn btn btn-outline-secondary" href="/users">
                           <span class="d-none d-sm-inline-block">Cancel</span>
                     </a> 
                     @if($allUsersCount == $errorCount)
                        <a class="closeBtn btn btn-outline-secondary" href="javascript:void(0);">
                           <span class="d-none d-sm-inline-block">Import</span>
                     </a> 
                     @else 
                     <button type="submit" class="dt-button create-new btn btn-primary" >
                           <span class="d-none d-sm-inline-block">Import</span>
                     </button> 
                     @endif
                     </div>
                  </div>
               </div>
               <div class="container">
                  <div class="row">
                     <div class="col-xs-12">
                        <table class="datatables-basic table border-top dataTable no-footer dtr-column dt-responsive" id="importUserDT" aria-describedby="DataTables_Table_0_info">
                           <thead>
                              <tr>
                                 <th class="sorting">Fist Name</th>
                                 <th class="sorting">Last Name</th>
                                 <th class="sorting">Email</th>
                                 <th class="sorting">Mobile</th>
                              </tr>
                           </thead>
                           <tbody>
                                 @if(count($arrUsers))
                                    @foreach($arrUsers as $user)
                                    @php 
                                       $strikeStart = '';
                                       $strikeEnd = '';
                                       if($user['errorMessage'] !=''){
                                          $strikeStart = '<s style="color:red;text-decoration:line-through">';
                                          $strikeEnd = '</s>';
                                       }
                                    @endphp
                                    <tr>
                                       <td class="">{!! $strikeStart !!} {{ $user['firstname'] }} {!! $strikeEnd !!}</td>
                                       <td class="">{!! $strikeStart !!} {{ $user['lastname'] }} {!! $strikeEnd !!}</td>
                                       <td class="">{!! $strikeStart !!} {{ $user['email'] }} {!! $strikeEnd !!}</td>
                                       <td class="">{!! $strikeStart !!} {{ isset($user['mobile']) ? $user['mobile'] : '-' }} {!! $strikeEnd !!} </td>
                                    </tr>
                                    @endforeach
                                 @endif        
                           </tbody>
                        </table>

                        <div class="row" id="" style="padding:10px;">
                           <div class="col">
                              <div class="alert alert-warning alert-dismissible" role="alert">
                                 <span id="">Note: Duplicate Email id's are red colored and striked</span>					
                              </div>
                           </div>
                        </div>   
                     
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
@section('afterscripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/security/systemusers.js"></script>
<script>
    
</script>
@endsection
