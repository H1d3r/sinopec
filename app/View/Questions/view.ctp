 <?php foreach($lists as $val){?>
				<div class="TopicItem">
                                <div id="Download"><br></div>
                                <div class="TopicContent">
                                    <table border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td style="border: 0px;width: 80px">
                                                        <a href="#" style="color:white"><img src="/img/word.jpg" title="点击下载安装问题" width="60px" height="60px"></a>
                                                </td>
                                                <td style="border: 0px;">
                                                        <div style="font-size:150%"><?php echo $val['posts']['title']?></div>
                                                        <div>文件大小：<?php echo $val['posts']['size']?></div>                                                               
                                                        <div>更新日期：<?php echo date("Y-m-d",strtotime($val['posts']['created']))?></div>
                                                        <div><a href="/files/userfiles/<?php echo $val['attachments']['path']?>" style="color:blue;font-size:100%">点击下载</a></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                       
      <?php }?>                      
                      
