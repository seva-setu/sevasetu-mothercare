<?php 
$date=date('d/m/Y');
?>
<!DOCTYPE HTML>
<HTML>
<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
	@page {
2	            margin-top: 0px;
3	            margin-left: 0px;
4	        }
body { margin: 0px; }
html { margin: 0px}
	</style>
	<TITLE></TITLE>
</HEAD>
<BODY LANG="en-IN" LINK="#0000ff" DIR="LTR">
<TABLE WIDTH="100%" CELLPADDING="7" CELLSPACING="1">
		<TR>
			<TD COLSPAN=11 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #00000a; border-bottom: 2.25pt double #00000a; border-left: 1px solid #00000a; border-right: 1px solid #000001; padding: 0in 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3><B>SEVA
				SETU Mother Care Weekly Call List</B></FONT></FONT></FONT></P>
			</TD>
		</TR>
		<TR>
			<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding: 0in 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3><B>Date</B></FONT></FONT></FONT></P>
			</TD>
			<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3><?php echo $date; ?></FONT></FONT></FONT></P>
			</TD>
			<TD COLSPAN=9 VALIGN=TOP BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #000001; border-left: none; border-right: 1px solid #000001; padding: 0in 0.08in">
				<P><A NAME="_GoBack"></A><FONT COLOR="#000000">  <FONT FACE="Arial, serif"><FONT SIZE=3></FONT></FONT></FONT></P>
			</TD>
		</TR>
	<TBODY>
		<TR>
			<TD BGCOLOR="#a6a6a6" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding: 0in 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#ffffff"><FONT FACE="Arial, serif"><FONT SIZE=3><B>S.
				No.</B></FONT></FONT></FONT></P>
			</TD>
		<TD BGCOLOR="#a6a6a6" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#ffffff"><FONT FACE="Arial, serif"><FONT SIZE=3><B>Woman's
				name </B></FONT></FONT></FONT>
				</P>
			</TD>

<TD BGCOLOR="#a6a6a6" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#ffffff"><FONT FACE="Arial, serif"><FONT SIZE=3><B>Location</B></FONT></FONT></FONT></P>
			</TD>
<TD BGCOLOR="#a6a6a6" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#ffffff"><FONT FACE="Arial, serif"><FONT SIZE=3><B>Telephone
				number</B></FONT></FONT></FONT></P>
			</TD>
<TD BGCOLOR="#a6a6a6" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#ffffff"><FONT FACE="Arial, serif"><FONT SIZE=3><B>Alternate number</B></FONT></FONT></FONT></P>
			</TD>
			
			<TD BGCOLOR="#a6a6a6" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#ffffff"><FONT FACE="Arial, serif"><FONT SIZE=3><B>Intervention Date</B></FONT></FONT></FONT></P>
			</TD>
		
			
			  @if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1) 
			<TD BGCOLOR="#a6a6a6" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
				<P ALIGN=CENTER><FONT COLOR="#ffffff"><FONT FACE="Arial, serif"><FONT SIZE=3><B>Call champion</B></FONT></FONT></FONT></P>
			</TD>
			@endif
		</TR>




<!-- Data Fill -->
<?php foreach($result as $res){ ?>
	<TR>
		<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
			<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3></FONT></FONT></FONT>
			<?php echo (isset($res['v_unique_code'])) ? $res['v_unique_code'] : ""; ?> </P>
		</TD>

<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
			<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3></FONT></FONT></FONT>
				<?php echo (isset($res['v_name'])) ? $res['v_name'] : ""; ?></P>
		</TD>

<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
			<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3></FONT></FONT></FONT>
				@if(isset($res['v_village']) && $res['v_village']!=""){{{  $res['v_village'] or '' }}}, {{{ $res['v_taluka'] or '' }}}, {{{  $res['v_district'] or '' }}}@endif</P>
		</TD>

<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
			<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3></FONT></FONT></FONT>
				<?php echo (isset($res['v_phone_number'])) ? $res['v_phone_number'] : ""; ?></P>
		</TD>
		
		<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
			<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3></FONT></FONT></FONT>
				<?php echo (isset($res['v_phone_number'])) ? $res['v_alternate_phone_no'] : ""; ?></P>
		</TD>

<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
			<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3></FONT></FONT></FONT>
				<?php echo (isset($res['intervention_date'])) ? $res['intervention_date'] : ""; ?></P>
		</TD>

		  @if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1) 
<TD BGCOLOR="#ffffff" STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
			<P ALIGN=CENTER><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3></FONT></FONT></FONT>
				<?php echo (isset($res['champ_name'])) ? $res['champ_name'] : ""; ?></P>
		</TD>
		@endif
	</TR>

<?php } ?>
</TBODY>	
</TABLE>
</BODY>
</HTML>