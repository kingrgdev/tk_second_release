@extends('layouts.app')
@section('content')
<style>
    #dateTime, #cardHeader, #dateToday{
        font-weight:bold;
    }

    #dateTime{
        font-size:4.5em;
    }
    #cardHeader{
        background: linear-gradient(to top left, #2d79a3 20%, #223373 150%);
        font-size:1.5em;
        border-bottom: 0px solid #ddd
    }
    #dateToday{
        font-size:1.5em;
    }

    @media (max-width: 768px){
        #dateTime{
            font-size:2.5em;
        }
        #cardHeader{
            font-size:1em;
        }
        #dateToday{
            font-size:1em;
        }
    }

</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border: 0px">
                <div id="cardHeader" class="card-header text-center text-white">Welcome to Timekeeping</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <div class="text-center">
                        <span id="dateTime"></span>
                        <br>
                        <label id="dateToday"><?php echo date('l, F d,Y');?></label>
                    </div>
                                        
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function DateTime(id)
    {
        var date = new Date();
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        result = hours + ":" + minutes + ":" + seconds + " " + am_pm;
        document.getElementById(id).innerHTML = result;
        setTimeout('DateTime("'+id+'");','1000');
        return true;
    }
</script>

<script type="text/javascript">
    window.onload = DateTime('dateTime');
</script>

@endsection
