

<div class="table-responsive">
        <table id="table_punch_alteration_record" name="example" class="table table-hover" style="width:100%">
            <col>
            <colgroup span="2"></colgroup>
            <colgroup span="2"></colgroup>
            <thead>
                <tr>
                    <th rowspan="2" class="text-center">Date Applied</th>
                    <th colspan="2" scope="colgroup" class="text-center">Previous Log</th>                      
                    <th colspan="2" scope="colgroup" class="text-center">Applied Alteration</th>
                    <th rowspan="2" class="text-center">Reason</th>
                    <th colspan="2" scope="colgroup" class="text-center">Approval History</th>
                    <th rowspan="2" style="text-align: center;">Status</th>   
                    <th rowspan="2" style="text-align: center;">Action</th>                      
                    <tr>
                        <th scope="col">Time In</th>
                        <th scope="col">Time Out</th>
                        <th scope="col">Time In</th>
                        <th scope="col">Time Out</th>
                        <th scope="col">Level 1</th>
                        <th scope="col">Level 2</th>
                    </tr>                       
                </tr>          
            </thead>
            <tbody>
                @if(count($alteration_record) > 0)
                    @foreach($alteration_record as $record)
                        <tr>
                            @if($record->date_applied != "")
                            <td >{{date("F j Y",strtotime($record->date_applied))}}
                                <br>
                                <small>{{date("l",strtotime($record->date_applied))}}</small>
                            </td>
                            @else
                                <td></td>
                            @endif
                            @if($record->cur_time_in != "")
                                <td>{{date("F j Y \- h:i A",strtotime($record->cur_time_in))}}</td>
                            @else
                                <td></td>
                            @endif
                            @if($record->cur_time_out != "")
                            <td>{{date("F j Y \- h:i A",strtotime($record->cur_time_out))}}</td>
                            @else
                                <td></td>
                            @endif
                            @if($record->new_time_in != "")
                            <td>{{date("F j Y \- h:i A",strtotime($record->new_time_in))}}</td>
                            @else
                                <td></td>
                            @endif    
                            @if($record->new_time_out != "")
                            <td>{{date("F j Y \- h:i A",strtotime($record->new_time_out))}}</td>
                            @else
                                <td></td>
                            @endif 
                            <td style="text-align:center;">{{$record->reason}}</td>  
                            
                            
                                @if($record->approved_1 == "0")
                                    {{-- <td>{{$record->status}}</td>    --}}
                                    <td></td>
                                @else
                                    <td>{{$record->level1name}}</td>   
                                @endif
    
                                @if($record->approved_2 == "0")
                                    {{-- <td>{{$record->status}}</td> --}}
                                    <td></td> 
                                @else
                                    <td>{{$record->level2name}}</td> 
                                @endif

                                @if($record->status == "APPROVED")
                                <td style="color:#28a745; text-align:center;"><i id='txtremarks".$counter."' class='icon-right fa fa-check-circle'></i><b>APPROVED</b></td>
                                @endif

                                @if($record->status == "PENDING")
                                <td style='color:#E87B15; text-align:center;'><i id='txtremarks".$counter."' class='icon-right fa fa-question-circle'></i><b>PENDING</b></td>
                                @endif

                                @if($record->status == "CANCELLED")
                                <td style='color:#dc3545; text-align:center;'><i class='icon-right fa fa-times-circle'></i><b>CANCELLED</b></td>
                                @endif
    
                                @if($record->status == "PENDING")
                                    <td style="text-align:center;">
                                        <button type="button" class="btn btn-sm button red btn_Cancel" data-add="{{$record->id}}">Cancel Application</button>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    