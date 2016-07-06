<table>
 <tbody>
  <tr>
   <th>Unique Code</th>
   <th>Name</th>
   <th>Location</th>
   <th>Phone Number</th>
   <th>Alternate Number</th>
    <th>Intervention Date</th>
   @if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)  <th>Call Champion</th> @endif
     
  </tr>
  @foreach($result as $user)
  <tr>
   	<td>{{ $user['v_unique_code'] or '' }}</td>
   	<td>{{$user['v_name'] or ''}}</td>
 	<td>@if($user['v_village']!="") {{$user['v_village'] or ''}} {{$user['v_taluka'] or ''}} {{$user['v_district'] or ''}} @endif </td>
 	<td>{{$user['v_phone_number'] or ''}}</td>
 	<td>{{$user['v_alternate_phone_no'] or ''}}</td>
 	<td>{{$user['intervention_date'] or ''}}</td>
    @if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)  	<td>{{$user['champ_name'] or ''}}</td> @endif
  	
  </tr>
  @endforeach
 </tbody>
</table>