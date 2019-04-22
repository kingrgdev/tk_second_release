@extends('layouts/app')

@section('content')
<style>

</style>

<div id="pageContainer" class="container-fluid">
    <h1><i class="icon-right fa fa-upload" aria-hidden="true"></i>Import Biometrics</h1>
    
    <div id="moduleContainer" class="container-fluid">

        <form id="formImportBiometrics">
            <div class="col-md">
                <div class="form-group">
                    <div class=" pull-right">
                        <!-- <input type="file" name="file-1[]" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple /> -->
                        <input type="file" name="dtrfile" id="dtrfile" class="inputfile inputfile-1" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                        <label for="dtrfile"><i class="icon-right fa fa-upload" aria-hidden="true"></i><span>Choose a file&hellip;</span></label>    
                    </div>
                </div>
                <div class="form-group">
                    <input id="btnImport" name="btnImport" class="btn btn-sm button blue pull-right" type="button" value="Import Biometrics" style="width:150px; margin-bottom:20px"/>
                </div>
            </div>
        </form>
        

           
        <br><br>

        <div id="divEmployeeList" class="table-responsive">
            <table id="tableEmployeeList" name="tableEmployeeList" class="table table-striped" style="width:100%" >
                <thead>
                    <tr>
                        <th></th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Company</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Team</th>
                        <th>Employment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>    
                </tbody>
            </table>
        </div>

    </div>    
    
</div>
<script>
    $(document).on("click", "#btnImport", function(){
        //add alter
        var formData = new FormData();
        var file = document.getElementById("dtrfile").files[0];
        if(file == "" || file == null){
            alert("NO PATH FOUND");
        }
        else{
            formData.append("upfiles", file);
            if(confirm("Are you sure you want import this biometrics?") == true){
                $.ajax({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('uploadbiometrics') }}",
                    method: "POST",
                    data:formData,
                    processData: false,
                    contentType: false,
                    dataType: "json", 
                    success:function(data){
                        
                    },
                    error: function(xhr, ajaxOptions, thrownError){
                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }
        
    });
</script>





<script>
//file type addition
(function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.inputfile' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
}( document, window, 0 ));
</script>





@endsection