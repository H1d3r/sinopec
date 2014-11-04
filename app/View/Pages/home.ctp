<!-- 
<div class="home-text" style="font: 15px/1.5 '微软雅黑',Tahoma,Helvetica,'SimSun',sans-serif">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    中国石化统一通讯系统是为了满足中国石化员工在日常办公过程中进行安全便捷通讯的需求，提高协同办公的工作效率，增强业务协同的能力，在总部数据中心和香港海外分中心集中建设的内部沟通系统，向中国石化的境内外员工提供包括即时消息、即时消息、语音、视频、网络会议室、离线消息、群组、组织视图和消息推送等功能。
</div>
 -->

<!--<iframe src="/home/" height="500px" width="1280px"></iframe>-->
<iframe src="/home/" id="iframepage"  scrolling="no" marginheight="0" marginwidth="0"  ></iframe>
<script type="text/javascript">

	var width = $(window).width();
	var height = $(window).height();
		
		
window.onload = function() {
    
		
	// alert(width)	
//alert(window.innerWidth);     
    var iframe = document.getElementById("iframepage");
	//alert(window.innerHeight);
	iframe.style.width = (width - 13) + 'px';
    //iframe.iframe.style.width = (document.documentElement.clientWidth - 21) + 'px';
    //iframe.style.height = (window.innerHeight - 140) + 'px';
    iframe.style.height = (height -160 ) + 'px';
};

window.onresize = function() {
    var iframee = document.getElementById("iframepage");
    iframee.style.width = (width - 13)+ 'px';
    //iframee.style.height = (window.innerHeight -140)+ 'px';
    iframee.style.height = (height -160)+ 'px';
};
</script>