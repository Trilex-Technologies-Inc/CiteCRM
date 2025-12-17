<!-- Tool Bar -->

<td>
{literal}
<script language="JavaScript">
<!-- // START MENU CODE

// NOTE: If you use a ' add a slash before it like this \'\


StartMenu()


// MENU OPTIONS - you will find more options in the corporatestyle.css

MFL			= 221; 					// MENU DISTANCE FROM EDGE
MFT			= 212; 					// MENU DISTANCE FROM TOP
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
MO			= TMH-2;				// Y MENU OVERLAP CHANGE NUMBER VALUE
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


Top_Width[0]=125; Sub_Menu_Width[0]=125;
m[0]='Home';n[0]='index.php';st[0]="";s[0]=""
+l+"index.php"+r+"Home"+c
+l+"index.php?action=logout"+r+"Log Out"+c

Top_Width[1]=125; Sub_Menu_Width[1]=125;
m[1]='Company';n[1]='';st[1]="";s[1]=""
+l+"?page=control:company_edit"+r+"Edit"+c
+l+"?page=control:hours_edit"+r+"Office Hours"+c
+l+"?page=control:acl"+r+"Permisions"+c

Top_Width[2]=125; Sub_Menu_Width[2]=125;
m[2]='Billing Options';n[2]='';st[2]="";s[2]=""
+l+"?page=control:payment_options"+r+"Payment Methods"+c
+l+"?page=control:edit_rate"+r+"Billing Rates"+c

Top_Width[3]=150;Sub_Menu_Width[3]=125;
m[3]='CRM';n[3]='';st[3]="";s[3]=""
+l+"?page=control:check_updates&page_title=Check For Updates"+r+" Check For Updates "+c
+l+"?page=stats:hit_stats&page_title=Sats"+r+"Export Customers"+c
+l+"?page=stats:hit_stats&page_title=Sats"+r+"Export Employees"+c
+l+"?page=stats:hit_stats&page_title=Sats"+r+"Export Work Orders"+c
+l+"?page=stats:hit_stats&page_title=Sats"+r+"Import Customers"+c
+l+"?page=stats:hit_stats&page_title=Sats"+r+"Import Employees"+c
+l+"?page=stats:hit_stats&page_title=Sats"+r+"Import Work Orders"+c

Top_Width[4]=150;Sub_Menu_Width[4]=125;
m[4]='Stats';n[4]='?page=stats:main&page_title=Sats';st[4]="";s[4]=""
+l+"?page=stats:main&page_title=Sats"+r+" Office Stats "+c
+l+"?page=stats:hit_stats&page_title=Sats"+r+" Web Trafic"+c

// IF YOU ADD ANOTHER TOP LEVEL MENU YOU MUST ADD TO THE BOTTOM OF THIS LIST

ADJ[0]=MFL;
ADJ[1]=(Top_Width[0])+MFL;
ADJ[2]=(Top_Width[0]+Top_Width[1])+MFL;
ADJ[3]=(Top_Width[0]+Top_Width[1]+Top_Width[2])+MFL;
ADJ[4]=(Top_Width[0]+Top_Width[1]+Top_Width[2]+Top_Width[3])+MFL;
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
<!--

<td align="center">
	<a href="?page=core:main&page_title=Home" >Home</a></td>
<td>					
	<a href="?page=customer:view&page_title=Customers">Customers</a></td>
<td>						
	<a href="?page=workorder:main&page_title=Work Orders">Work Orders</a></td>
<td>	
	<a href="?page=employees:main&page_title=Employees" alt="employees">Employees</a></td>	
</td>
-->
