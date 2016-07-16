@if(!empty($result))
    @foreach($result as $res)
    <tr>
      <td><input type="checkbox" value="{{{ $res['bi_id'] or '' }}}" id="chkCheckedBox" name="chkCheckedBox[]"></td>
      <td data-title="{{ trans('routes.uniqueid') }}"><a href="{{ url() }}/admin/beneficiary/view/{{ Hashids::encode($res['bi_id']) }}">{{{ $res['v_unique_code'] or '' }}}</a></td>
      <td data-title="{{ trans('routes.name') }}"><a href="{{ url() }}/admin/beneficiary/view/{{ Hashids::encode($res['bi_id']) }}">{{{ $res['v_name'] or '' }}}</a></td>
       <td  @if(isset($res['v_village']) && $res['v_village']!="") data-title="{{ trans('routes.location') }}" @endif>@if(isset($res['v_village']) && $res['v_village']!=""){{{  $res['v_village'] or '' }}}, {{{ $res['v_taluka'] or '' }}}, {{{  $res['v_district'] or '' }}}@endif</td>
        <td data-title="{{ trans('routes.phonenumber') }}">{{{  $res['v_phone_number'] or '' }}}</td>
      <td @if($res['v_alternate_phone_no']!="") data-title="{{ trans('routes.alternateno') }}" @endif>{{{ $res['v_alternate_phone_no'] or '' }}}</td>
      <td data-title="{{ trans('routes.interventionpoint') }}">{{{  $res['intervention_date'] or '' }}}</td>
   	 @if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)     <td @if($res['champ_name']!="") data-title="{{ trans('routes.callchampion') }}" @endif>{{{  $res['champ_name'] or '' }}}</td> @endif
     </tr>                	
	@endforeach
@endif