@extends('layouts.admin')


@section('content')
<div class="content-wrapper height-100">
  @if(isset($success))
    <div class="alert alert-success">
        <strong>Success!</strong> {{$success}}
    </div>
  @endif
  @if(session('success'))
      <div class="alert alert-success">
          {{session('success')}}
      </div>
  @endif
  @if(count($errors))

    <div class="alert alert-danger">

      <strong>Whoops!</strong> There were some problems with your input.

      <br/>

      <ul>

        @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

      </ul>

    </div>

  @endif

  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('AddBlogPost') }}" >
    @csrf
    <h5>Add Blog</h5>
    <div class="form-group ">
      <label class="control-label col-sm-2" >Title:</label>
      <div class="col-sm-10">
        <input type="text" name="title" value="{{ old('title') }} " required class="form-control {{ $errors->has('title') ? 'has-error' : '' }}" id="title" placeholder="Enter title">
        <span class="text-danger">{{ $errors->first('title') }}</span>
      </div>
    </div>
    <div class="form-group ">
      <label class="control-label col-sm-2" for="content">content</label>
      <div class="col-sm-10">
        <textarea name="content"  required class="form-control {{ $errors->has('content') ? 'has-error' : '' }}" id="content" placeholder="Enter Blog Content">{{ old('content') }} </textarea>
        <span class="text-danger">{{ $errors->first('content') }}</span>
      </div>
    </div>
    <div class="form-group ">
      <label class="control-label col-sm-2" for="image">image</label>
      <div class="col-sm-10">
        <input type="file" name="image">
        <span class="text-danger">{{ $errors->first('image') }}</span>
      </div>
    </div>
    <div class="form-group ">
      <label class="control-label col-sm-2" for="email">Tags</label>
      <div class="col-sm-10">
        <input type="text" name="tags" value="{{ old('tags') }}" required class="form-control {{ $errors->has('tags') ? 'has-error' : '' }}" id="tags" placeholder="seperate tags with comma">
        <span class="text-danger">{{ $errors->first('tags') }}</span>
      </div>
    </div>
    <div class="form-group "> 
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success">Submit</button>
        <a href="{{route('Feed')}}"><button type="button" class="btn btn-default">Cancel</button></a>
      </div>
    </div>
  </form>
  <hr>
    <div class="table-responsive">  
      <table id="datatable" class="table table-bordered mt-2">
        <thead>
            
            <tr>
              <th>Title</th>
              <th>Content</th>
              <th>Tags</th>
              <th>image</th>
              <th>Action</th>
              
            </tr>
        </thead>
        <tbody>
        @foreach($blogs as $blog) 
      
        <tr id="id{{$blog->id}}">
            <td>{{$blog->title}}</td>
             <td>{!!substr($blog->content, 0,50)!!}</td>
            <td>{{implode(',', unserialize($blog->tags)) }}</td>
            <td><img src="{{asset('uploads/blog/').'/'.$blog->image}}" height="50px" width="75px"></td>
            <td><a href="{{route('BlogDelete')}}" data-id="{{$blog->id}}" id="delete" onclick="return confirm('Are you sure you want to Remove?');"><button class="fa fa-trash"></button></a>
             <a href="{{route('UpdateBlog',$blog->id)}}" ><button class="fa fa-edit"></button></a>
            <!-- <a href="{{route('ViewBlog',$blog->id)}}" ><button class="fa fa-eye"></button></a> --></td>
        </tr>
         
        @endforeach
        </tbody>
      </table>
    </div>
</div>  
@endsection
@section('scripts')
  <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#datatable').DataTable({"pageLength": 50});
          $('#search-category').on('keyup', function(){
              
              table
              .column(0)
              .search(this.value)
              .draw();

            });
          $('#search-date2').on('keyup', function(){
              
              table
              .column(1)
              .search(this.value)
              .draw();

            });
          $(document).on('click','#delete',function(e){

                e.preventDefault();     
                var id = $(this).data('id');
                var url = $(this).attr('href');
                if(id){
                    $.ajax({       
                    url:url,
                    method:"POST",
                    dataType: "json", 
                    data:{id:id},               
                    //beforeSend:showLoader,
                    success: function(resp){
                      if(resp.result==1)
                      {
                        $('#id'+id).hide(1500);
                      }
                       
                    },
                    //error:hideLoader
                   });
                }
            });
      });
  </script> 
  <script>
    CKEDITOR.replace( 'content' );
  </script>
  
@endsection
