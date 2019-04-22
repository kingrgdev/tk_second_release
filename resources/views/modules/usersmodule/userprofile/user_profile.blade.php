<style>
    #empPic{
        height:135px;
        width:135px;
        padding:3px;
        border:1px solid #3C8DBC;
        border-radius:50%;
        float:left;
        object-fit:contain;
        position:relative;
        background-color:#F8FAFC;
        margin-right:10px;
    }
    #empInfo{
        font-size:1.2em;
    }
    @media (max-width: 768px){
        #moduleContainer{
            padding:5px;
        }
        #empInfo{
            font-size:.85em;
        }
        #empPic{
            margin-top:7px;
            margin-right:4px;
            height:76px;
            width:76px;
        }
}
</style>

<div id="moduleContainer" class="container-fluid">
            <div class="col-md-8">
                {{-- user picture --}}
                <img id="empPic" class="img-responsive" src="/images/2x2.jpg">
        
                <div id="userInfo" class="row">
        
                    <div class="col-md-8"><strong>Name:</strong>
                        <span id="emp_name"></span>
                    </div>
                    
                    <div class="col-md-8" ><strong>Department:</strong>
                        <span id="emp_dept"></span>
                    </div>
        
                    <div class="col-md-8" ><strong>Position:</strong>
                        <span id="emp_position"></span>
                    </div>
        
                    <div class="col-md-8" ><strong>Manager:</strong></div>
        
                    <div class="col-md-8" ><strong>Shift:</strong>
                        <span id="emp_shift"></span>
                    </div>
        
                    <div class="col-md-8" ><strong>Remaining Late Grace:</strong></div>
                </div>
            </div>
        </div>

    <script>
            $(document).ready(function(){
                $.ajax({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('userprofile') }}",
                    method: "GET",
                    data:{}, 
                    dataType: "json",
                    success:function(data)
                    {
                       $('#emp_name').html(data.name);
                       $('#emp_position').html(data.position);
                       $('#emp_shift').html(data.shift);
                       $('#emp_dept').html(data.dept);
                    }
                });
            });
        </script>