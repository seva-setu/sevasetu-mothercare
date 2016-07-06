<?php 
$tag=isset($searchTag)?$searchTag:"";
if($tag!="")
	$display="display:block;";
else 
	$display="display:none;";
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
$userinfo=Session::get('user_logged');
?>
@include('template.admin_title')
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
<div id="content">
	<div id="content-header">
    	<h1><?php echo trans('routes.callchampion'); ?></h1>
	</div>
	<div id="breadcrumb">
    	<a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i><?php echo trans('routes.home'); ?></a><a class="current"><?php echo trans('routes.callchampion'); ?></a> 
   	</div>
	<div class="container-fluid"> 
    <br>
    	<div class="span10" style="margin-left:0;">
    	<div class="span4" style="float:left;">
    	<div style="width: 20%"><?php echo trans('routes.search'); ?></div>
		<div style="width: 80%;position: relative;" id="keywordtextbox">
			<input placeholder="<?php echo trans('routes.searchlabel'); ?>" type="text" name="searchadministrator" id="searchadministrator" style="width:99%;" class="input-new search-input" title="<?php echo trans('routes.searchlabel'); ?>" onKeyUp="clearText(this.id)" value="<?php echo $tag; ?>" >
			<?php if($tag!=""){?>
				<a class="search-reset searchadministrator" id="search-reset" style="<?php echo $display; ?>" href="<?php echo Config::get('app.url').'admin/callchampions'?>"></a>
			<?php }else{?>
				<div class="search-reset searchadministrator" id="search-reset" onClick="hidesearch(this.id);"></div>
			<?php }?>
	    </div>
    	</div>
    	<?php if($userinfo['v_role']==0 || $userinfo['v_role']==1){?>
        <div class="span5" style="float:right;">
        <span class="insertDelMultipleButton">
  			<a href="<?php echo Config::get('app.url'); ?>admin/callchampions/edit" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i><?php echo trans('routes.addcallch'); ?></a>
    		<a href="javascript:void(0);" onClick="return checkDelete();" class="btn btn-danger"><i class="icon-remove icon-white"></i><?php echo trans('routes.deletecallch'); ?></a>
  		</span>
        	</div>
        	<?php }?>
        </div>
        <?php  echo Session::get('message'); ?>
        <?php  
			//$attributes = array('class' => 'form-horizontal', 'id' => 'frmList', 'name' => 'frmList');
			//echo form_open(SITEURLADM.$cntrlName.'/deleteSelected',$attributes);?>
		   	<?php //echo $this->session->flashdata('dispMessage');?> 
		  	<form class="form-horizontal" accept-charset="utf-8" role="form" method="POST" id="frmList" name="frmList" action="<?php echo Config::get('app.url').'admin/callchampions/deleteSelected'; ?>">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
				            <h5><?php //echo ucfirst($formTitle);?></h5>
          				</div>
			 		<div class="widget-content ">
	        <div id="replace_pagecontant">	 	
	        	
			<div id="no-more-tables">
                    <div id="service_table" class="service_table">
                    
         	 <table class="table table-hover with-check table-condensed cf">
                       <thead class="cf mar-btn">
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onClick="checkedAll('frmList');" /></th>
                  <th>{{{ trans('routes.uniqueid') }}}</th>
                  <th>{{{ trans('routes.username') }}}</th>
				  <th>{{{ trans('routes.email') }}}</th>
				  <th>{{{ trans('routes.language') }}}</th>
				  <th>{{{ trans('routes.phonenumber') }}}</th>
				  <th>{{{ trans('routes.profession') }}}</th>
				  <th>{{{ trans('routes.action') }}}</th>
                </tr>
              </thead>
              <?php if(count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){
              		$check="";
              		foreach ($languagedata as $lang){
              			if($value->v_language!=""){
              				$lanarr=explode(",", $value->v_language);
              				if(in_array($lang->bi_id,$lanarr))
              					$check.=$lang->v_language.", ";
              			}
              		}
              	?>
			        <tr>
                      <td><input type="checkbox" name="chkCheckedBox[]" id="chkCheckedBox" value="<?php echo $value->bi_id; ?>_<?php echo $value->bi_user_login_id; ?>" /></td>
                      	<td data-title="{{{ trans('routes.uniqueid') }}}"><a href="<?php echo Config::get('app.url').'admin/callchampions/view/'.Hashids::encode($value->bi_id) ?>">{{{ $value->v_unique_code }}}</a></td>
                        <td data-title="{{{ trans('routes.username') }}}"><a href="<?php echo Config::get('app.url').'admin/callchampions/view/'.Hashids::encode($value->bi_id) ?>">{{{ $value->v_name }}}</a></td>
                      	<td data-title="{{{ trans('routes.email') }}}"><?php echo $value->v_email;?></td>
						<td data-title="{{{ trans('routes.language') }}}"><?php echo ucwords(trim(trim($check),","));?></td>
						<td data-title="{{{ trans('routes.phonenumber') }}}"><?php echo $value->v_phone_number;?></td>
					  	<td data-title="{{{ trans('routes.profession') }}}"> {{{ ucfirst($value->v_profession) }}}</td>
					  	
					
					  	<td data-title="{{{ trans('routes.action') }}}">
					  	  	@if(isset($value->bi_calls_champion_id) && $value->bi_calls_champion_id !="")
                      			<a class="btn btn-default" href="{{ url() }}/admin/beneficiary/filterbycallchampion/{{ Hashids::encode($value->bi_id) }}"> {{ trans('routes.viewassignben') }}</a>					  	
                       		@endif
						</td>
                    </tr>                	
                <?php } ?>
              </tbody>
              <?php } else { ?>
              <tbody>
              	  <tr>
                    <td colspan="10"><center><em><?php echo trans('routes.norecord'); ?></em></center></td>
                  </tr>
              </tbody>
              <?php } ?>
            </table>
           </div></div>
           <!-- End of nomoretables -->
            </div>
          </div>
        </div>
      </div>
      
       <div class="widget-box">
       <div class="pagination" style="float:right;clear:both;"><?php echo $result->render(); ?></div>
       </div>
    </div>
    </form>
    <!--</form>-->
    <?php 
    //echo form_close();
    ?>
    @include('template/admin_footer')
  </div>
</div>
@include('template/admin_jsscript')
<script>
	var siteurl="<?php echo Config::get('app.url')?>";
 /*   $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getPosts(page);
            }
        }
    });

    $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });

    function getPosts(page) {
        $.ajax({
            url : siteurl+'admin/callchampions?page=' + page,
            dataType: 'html',
        }).done(function (data) {
            $('#replace_pagecontant').html(data);
            location.hash = page;
        }).fail(function () {
        	alert('Posts could not be loaded.');
        });
    }
*/
    </script>


  <script language="javascript" type="text/javascript">

		$('#searchadministrator').bind('keypress', function(e) 
		{	
			$("#search-reset").show();
			if(e.keyCode==13)
			{
			  if(!$('#hdnData').val())	
			  {		
				  var value = $('#searchadministrator').val();
				  if(value !=""){		  
				  window.location="<?php echo Config::get('app.url'); ?>admin/callchampions/searchdatacallchampion?search="+encodeURIComponent(value);
				  }
			  }	  
			}
		});

					$("#searchadministrator").coolautosuggest({

						

						url:"<?php echo Config::get('app.url'); ?>admin/callchampions/autocompletecallchampion?chars=",

						idField:$("#hdnData"),

					//	submitOnSelect:true,

						onSelected:function(result){

							if(result.fullname==1)

							{

									var fullname=result.fullname;	

							}

						  // Check if the result is not null

						  if(result!=null)

						  {				

						  				  				  

						  		window.location="<?php echo Config::get('app.url'); ?>admin/callchampions/searchdatacallchampion/"+result.id+'/'+result.data;

						  }						 

						},		

					});

				</script> 