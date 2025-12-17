<!-- Tool Bar -->

<td>
{literal}
<script language="JavaScript">
<!-- // START MENU CODE

// NOTE: If you use a ' add a slash before it like this \'\


StartMenu()


// MENU OPTIONS - you will find more options in the corporatestyle.css

MFL			= 221; 					// MENU DISTANCE FROM EDGE
MFT			= 211; 					// MENU DISTANCE FROM TOP
ALIGN		= "left"				// MENU LEFT OR RIGHT
TMH			= 22;					// TOP MENU HEIGHT
TMFS		= "8";					// TOP MENU FONT SIZE
TMFW		= "bold";				// TOP MENU FONT WEIGHT bold/normal
TMFF		= " arial, verdana, helvetica, sans";	// TOP MENU FONT FACE
TMC			= "6699FF";				// TOP MENU OFF FONT COLOR
TMBC		= "6699FF";				// TOP MENU OFF BACKGROUND COLOR
TMBI		= "images/swsh.gif";			// TOP MENU OFF BACKGROUND IMAGE
TMHC		= "FFFFFF";				// TOP MENU HOVER TEXT COLOR
TMHBC		= "6699FF";				// TOP MENU HOVER BACKGROUND COLOR
TMHBI		= "images/menuon.gif";			// TOP MENU HOVER BACKGROUND IMAGE
MO			= TMH-0;				// Y MENU OVERLAP CHANGE NUMBER VALUE
SUBshift	= 0;					// SHIFT SUBMENU RIGHT



// START SUBMENU OPTIONS - you will find more options in the corporatestyle.css


SMH		= 22;					// SUB MENU HEIGHT
SMFS		= "8";					// SUB MENU FONT SIZE
SMFW		= "normal";				// SUB MENU FONT WEIGHT bold/normal
SMFF		= "arial,MS Sans Serif,sans-serif";	// SUB MENU FONT FACE
SMC		= "000000";				// SUB MENU OFF FONT COLOR
SMBC		= "FFFFFF";				// SUB MENU OFF BACKGROUND COLOR
SMHC		= "FFFFFF";				// SUB MENU HOVER TEXT COLOR
SMHBC		= "6699FF";				// SUB MENU HOVER BACKGROUND COLOR


SubMenu()




// START MENU NUMBER 1 COPY AND PASTE A GROUP TO ADD A NEW TOP LEVEL SEE NOTE BELOW


Top_Width[0]=120; Sub_Menu_Width[0]=120;
m[0]='{/literal}{$translate_menu_home}{literal}';n[0]='index.php';st[0]="";s[0]=""
+l+"index.php"+r+"{/literal}{$translate_menu_home}{literal}"+c
+l+"index.php?action=logout"+r+"{/literal}{$translate_menu_log_out}{literal}"+c

Top_Width[1]=120; Sub_Menu_Width[1]=150;
m[1]='{/literal}{$translate_menu_customers}{literal}';n[1]='?page=customer:view&page_title={/literal}{$translate_menu_customers}{literal}';st[1]="";s[1]=""
+l+"?page=customer:view&page_title={/literal}{$translate_menu_customer_search}{literal}"+r+"{/literal}{$translate_menu_search}{literal}"+c
+l+"?page=customer:new&page_title={/literal}{$translate_menu_add_new_customer}{literal}"+r+"{/literal}{$translate_menu_new}{literal}"+c

{/literal}
	{if $customer_details[i].CUSTOMER_ID != ''}
		{literal}
			+l+"?page=customer:edit&customer_id={/literal}{$customer_details[i].CUSTOMER_ID}{literal}&page_title={/literal}{$translate_menu_edit_customer}{literal}"+r+"{/literal}{$translate_menu_edit}{literal}"+c
			+l+"?page=billing:new_gift&customer_id={/literal}{$customer_details[i].CUSTOMER_ID}{literal}&page_title={/literal}{$translate_menu_new_gift}{literal}&customer_name={/literal}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{literal}"+r+"{/literal}{$translate_menu_gift_cert}{literal}"+c
			+l+"?page=customer:delete&customer_id={/literal}{$customer_details[i].CUSTOMER_ID}{literal}&page_title={/literal}{$translate_menu_delete_customer}{literal}"+r+"{/literal}{$translate_menu_delete}{literal}"+c
		{/literal}
	
	{/if}
{literal}


Top_Width[2]=120; Sub_Menu_Width[2]=150;
m[2]='{/literal}{$translate_menu_work_orders}{literal}';n[2]='?page=workorder:main&page_title={/literal}{$translate_menu_work_orders}{literal}';st[2]="";s[2]=""
	+l+"?page=workorder:main&page_title={/literal}{$translate_menu_work_orders}{literal}"+r+"{/literal}{$translate_menu_open_work_orders}{literal}"+c	
	+l+"?page=workorder:view_closed&page_title={/literal}{$translate_menu_closed_work_orders}{literal}"+r+"{/literal}{$translate_menu_closed_work_orders}{literal}"+c
{/literal}
	{if $customer_details[i].CUSTOMER_ID != ''}
		{literal}
			+l+"?page=workorder:new&customer_id={/literal}{$customer_details[i].CUSTOMER_ID}{literal}&page_title=Create New Work Order"+r+"New Work Order"+c
		{/literal}
	{else}
		{literal}
			+l+"index.php?page=customer:view&page_title={/literal}{$translate_menu_customer_search}{literal}"+r+"{/literal}{$translate_menu_new}{literal}"+c
		{/literal}
	{/if}
	
	{if $single_workorder_array[i].WORK_ORDER_ID != ''}
		
		{if $single_workorder_array[i].WORK_ORDER_STATUS != "6" }
			{literal}
				+l+"?page=workorder:new_note&wo_id={/literal}{$single_workorder_array[i].WORK_ORDER_ID}{literal}&page_title={/literal}{$translate_menu_new_note}{literal}"+r+"{/literal}{$translate_menu_new_note}{literal}"+c
				
			{/literal}

			

			{if $single_workorder_array[i].WORK_ORDER_STATUS == "10" }
				{if $part == "1"}
					{if $single_workorder_array[i].WORK_ORDER_CURENT_STATUS =="3"}
						{literal}
						+l+"?page=parts:update&wo_id={/literal}{$single_workorder_array[i].WORK_ORDER_ID}{literal}&page_title={/literal}{$translate_menu_recieved_parts}{literal}"+r+"{/literal}{$translate_menu_recieved_parts}{literal}"+c
						{/literal}
					{/if}
				{else}
					{literal}
						+l+"?page=parts:main&wo_id={/literal}{$single_workorder_array[i].WORK_ORDER_ID}{literal}&page_title={/literal}{$translate_menu_order_parts}{literal}"+r+"{/literal}{$translate_menu_order_parts}{literal}"+c
					{/literal}
				{/if}
			{/if}
			
		{/if}
		
		
		{if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME !=""}
			
			{if $single_workorder_array[i].WORK_ORDER_STATUS == "10" }
				{literal}
					+l+"?page=workorder:close&wo_id={/literal}{$single_workorder_array[i].WORK_ORDER_ID}{literal}&page_title={/literal}{$translate_menu_close_work_order}{literal} {/literal}{$single_workorder_array[i].WORK_ORDER_ID}{literal}"+r+"{/literal}{$translate_menu_close}{literal}"+c
				{/literal}
			{/if}
		{/if}
		
		{literal}
		+l+"?page=workorder:print&wo_id={/literal}{$single_workorder_array[i].WORK_ORDER_ID}{literal}&page_title={/literal}{$translate_menu_print}{literal}&escape=1"+r+"{/literal}{$translate_menu_print}{literal}"+c
		{/literal}
		
		{if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME  != ""}
			{if $single_workorder_array[i].WORK_ORDER_STATUS != "6"}
				{literal}
					+l+"?page=invoice:new&wo_id={/literal}{$single_workorder_array[i].WORK_ORDER_ID}{literal}&page_title={/literal}{$translate_menu_invoice}{literal}&customer_id={/literal}{$single_workorder_array[i].CUSTOMER_ID}{literal}"+r+"{/literal}{$translate_menu_invoice}{literal}"+c
				{/literal}
			{/if}
		{/if}
		
	{/if}
{literal}



Top_Width[3]=120; Sub_Menu_Width[3]=120;
m[3]='{/literal}{$translate_menu_employees}{literal}';n[3]='?page=employees:main&page_title={/literal}{$translate_menu_employees}{literal}';st[3]="";s[3]=""
	+l+"?page=employees:main&page_title={/literal}{$translate_menu_search}{literal}"+r+"{/literal}{$translate_menu_search}{literal}"+c
	+l+"?page=employees:new&page_title={/literal}{$translate_menu_new}{literal}"+r+"{/literal}{$translate_menu_new}{literal}"+c
{/literal}
	{if $employee_details[i].EMPLOYEE_ID != ''}
		{literal}
			+l+"?page=employees:edit&employee_id={/literal}{$employee_details[i].EMPLOYEE_ID}{literal}&page_title={/literal}{$translate_menu_edit}{literal}"+r+" {/literal}{$translate_menu_edit}{literal} "+c
		{/literal}
	{/if}
{literal}

Top_Width[4]=120;Sub_Menu_Width[4]=120;
m[4]='{/literal}{$translate_menu_invoice}{literal}';n[4]='?page=invoice:view_paid&page_title={/literal}{$translate_menu_invoice}{literal}';st[4]="";s[4]=""
+l+"?page=invoice:view_paid&page_title={/literal}{$translate_menu_paid_2}{literal}"+r+"{/literal}{$translate_menu_paid}{literal}"+c
+l+"?page=invoice:view_unpaid&page_title={/literal}{$translate_menu_un_paid_2}{literal}"+r+"{/literal}{$translate_menu_un_paid}{literal}"+c
+l+"?page=parts:status&status=1&page_title={/literal}{$translate_menu_open_orders}{literal}"+r+"{/literal}{$translate_menu_open_orders}{literal}"+c
+l+"?page=parts:status&status=0&page_title={/literal}{$translate_menu_closed_orders}{literal}"+r+"{/literal}{$translate_menu_closed_orders}{literal}"+c

Top_Width[5]=120;Sub_Menu_Width[5]=120;
m[5]='Help';n[5]='';st[5]="";s[5]=""
+l+"http://www.citecrm.com/docs/"+r+"{/literal}{$translate_core_documentation}{literal}"+c
+l+"http://www.citecrm.com/bugs"+r+"{/literal}{$translate_core_report_bug}{literal}"+c
+l+"?page=control:main&page_title={/literal}{$translate_core_control}{literal}"+r+"{/literal}{$translate_core_control}{literal}"+c
+l+"http://www.citecrm.com"+r+"Cite CRM"+c
+l+"http://forums.citecrm.com/"+r+"Forums"+c





// IF YOU ADD ANOTHER TOP LEVEL MENU YOU MUST ADD TO THE BOTTOM OF THIS LIST

ADJ[0]=MFL;
ADJ[1]=(Top_Width[0])+MFL;
ADJ[2]=(Top_Width[0]+Top_Width[1])+MFL;
ADJ[3]=(Top_Width[0]+Top_Width[1]+Top_Width[2])+MFL;
ADJ[4]=(Top_Width[0]+Top_Width[1]+Top_Width[2]+Top_Width[3])+MFL;
ADJ[5]=(Top_Width[0]+Top_Width[1]+Top_Width[2]+Top_Width[3]+Top_Width[4])+MFL;



MENU=m.length

for (i=0; i < MENU; i++){


// START WRITING TOP LEVEL MENUS


document.write("<div style='position:absolute;"+ALIGN+":"+ADJ[i]+";top:"+MFT+";width:"+Top_Width[i]+"' onmouseover='o["+i+"].ShowMenu()' onmouseout='o["+i+"].HideMenu()'>")

browser_version= parseInt(navigator.appVersion);
browser_type = navigator.appName;
if (browser_type == "Netscape") {
document.write("<a class='menu_TOP' style='height:"+TMH+"; color:#"+TMC+"; background-image: url("+TMBI+"); background-color:#"+TMBC+"; font-size:"+TMFS+"pt; font-weight:"+TMFW+"; font-family: "+TMFF+"; "+spn+"' href='"+n[i]+"'>"+m[i]+"</a></div>")
}
else {

document.write("<a class='menu_TOP' style='height:"+TMH+"; color:#"+TMC+"; background-image: url("+TMBI+"); background-color:#"+TMBC+"; font-size:"+TMFS+"pt; font-weight:"+TMFW+"; font-family: "+TMFF+"; "+spn+"' onmouseover=\"this.style.backgroundColor='#"+TMHBC+"';this.style.color='"+TMHC+"';this.style.backgroundImage='URL("+TMHBI+")'\"  onmouseout=\"this.style.backgroundColor='#"+TMBC+"';this.style.color='"+TMC+"';this.style.backgroundImage='URL("+TMBI+")'\" href='"+n[i]+"'>"+m[i]+"</a></div>")

}

}


for (i=0; i < MENU; i++){

// START WRITING SUB MENUS


document.write("<div id='SUB"+i+"' class='menu_DIV' style='position: absolute; "+ALIGN+":"+(ADJ[i]+SUBshift)+";top:"+(MFT+MO)+";width:"+Sub_Menu_Width[i]+";background-color:#"+SMBC+";' onmouseover='o["+i+"].ShowMenu()' onmouseout='o["+i+"].HideMenu()'>"+s[i]+"</div>")


}



function StartMenu()
{

var D6=window,Y7=document;
function DETECT()
{
this.ver=navigator.appVersion;this.agent=navigator.userAgent;this.dom=Y7.getElementById?1:0;this.opera5=this.agent.indexOf("Opera 5")>-1;this.ie5=(this.ver.indexOf("MSIE 5")>-1 && this.dom && !this.opera5)?1:0;this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom && !this.opera5)?1:0;this.ie4=(Y7.all && !this.dom && !this.opera5)?1:0;this.ie=this.ie4||this.ie5||this.ie6;this.mac=this.agent.indexOf("Mac")>-1;this.ns6=(this.dom && parseInt(this.ver)>=5)?1:0;this.ns4=(Y7.layers && !this.dom)?1:0;this.BWD=(this.ie6||this.ie5||this.ie4||this.ns4||this.ns6||this.opera5);return this
}
BWD=new DETECT();z=0;b=0;
spn="";
if(BWD.opera5||BWD.ns6)
{
b=2
};
if(BWD.ie)
{
spn=" width: 100%"
}else{
z=6
}

} 


function SubMenu()
{

document.write("<TABLE cellpadding='0' cellspacing='0' border='0' ><tr><td>");
document.write("</td></tr></table>");

document.write("<div  style='height:"+TMH+";position:absolute;top:"+MFT+";background-image: url("+TMBI+"); background-color:#"+TMBC+";z-level:-2'></div>")

browser_version= parseInt(navigator.appVersion);
browser_type = navigator.appName;
if (browser_type == "Netscape") {
l="<a class='menu_SUB' style='height:"+SMH+"; color:#"+SMC+"; background-color:#"+SMBC+"; font-size:"+SMFS+"pt; font-weight:"+SMFW+"; font-family: "+SMFF+";"+spn+"' href='";
}
else {
l="<a class='menu_SUB' style='height:"+SMH+"; color:#"+SMC+"; background-color:#"+SMBC+"; font-size:"+SMFS+"pt; font-weight:"+SMFW+"; font-family: "+SMFF+";"+spn+"' onmouseover=\"this.style.backgroundColor='#"+SMHBC+"';this.style.color='"+SMHC+"'\"  onmouseout=\"this.style.backgroundColor='#"+SMBC+"';this.style.color='"+SMC+"'\" href='";
}
r="'>";
c="</a>";


m=new Array();n=new Array();s=new Array();Sub_Menu_Width=new Array();su=new Array();st=new Array();Top_Width=new Array();ADJ=new Array()

}


function lib_obj(obj,nest){nest=(!nest) ? "":'document.'+nest+'.';this.evnt=BWD.dom? document.getElementById(obj):BWD.ie4?document.all[obj]:BWD.ns4?eval(nest+"document.layers." +obj):0;this.css=BWD.dom||BWD.ie4?this.evnt.style:this.evnt;this.ref=BWD.dom||BWD.ie4?document:this.css.document;this.x=parseInt(this.css.top)||this.css.pixeltop||this.evnt.offsettop||0;this.y=parseInt(this.css.left)||this.css.pixelleft||this.evnt.offsetleft||0;return this}
function lib_doc_size(){this.x=0;this.x2=BWD.ie && document.body.offsetWidth-20||innerWidth||0;this.y=0;this.y2=BWD.ie && document.body.offsetHeight-5||innerHeight||0;this.x50=this.x2/2;this.y50=this.y2/2;return this;}
lib_obj.prototype.ShowMenu = function(){this.css.visibility="visible"}
lib_obj.prototype.HideMenu = function(){this.css.visibility="hidden"}
function libinit(){page=new lib_doc_size();o=new Array();for (i=0; i < MENU; i++){o[i]=new lib_obj('SUB'+i);o[i].HideMenu()}}
libinit()


// END MENU CODE -->
</script>
{/literal}
<br>
</td>
