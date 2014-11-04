<?php 
foreach ($softwares as $software): //pr($software); ?>
<!--     <tr>
        <td><?php echo h($user['User']['id']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['password']); ?>&nbsp;</td>
        <td>
            <?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?>
        </td>
        <td><?php echo h($user['User']['created']); ?>&nbsp;</td>
        <td><?php echo h($user['User']['modified']); ?>&nbsp;</td>
        <td class="actions">
            <?php echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id'])); ?>
            <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id'])); ?>
            <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), array(), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
        </td>
    </tr> -->

<div class="TopicItem">
    <div id="Download"><br></div>
    <div class="TopicContent">
        <table border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td style="border: 0px;width: 80px;vertical-align:top">
                            <a href="#" style="color:white;"><img src="/img/1.png" title="<?php echo h($software['Software']['name']); ?>" width="60px" height="60px" style="margin-top:10px"></a>
                        </td>
                        <td style="border: 0px;">
                                <div style="font-size:150%"><?php echo h($software['Software']['name']); ?>&nbsp;</div>
                                <div>支持的操作系统：<?php echo h($software['Software']['systems']); ?>&nbsp;</div>
                                <div>安装包大小：<?php echo h($software['Software']['size']); ?>&nbsp;</div>
                                <div>版本：<?php echo h($software['Software']['version']); ?>&nbsp;</div>
                                <div>更新日期：<?php echo h($this->Time->format($software['Software']['created'],'%Y-%m-%d')); ?>&nbsp;</div>
                                <div>更新内容：
                                            <a class="btnShow" style="color:blue;font-size:100%;cursor:pointer">详细</a>
                                            <a class="btnHide" style="color: blue; font-size: 100%; cursor: pointer; display: none;">收起</a>
                                            <div id="div_text" style="display: none;">
                                                    <?php echo str_replace("\r\n", "<br/>",$software['Software']['body']); ?>&nbsp;
                                            </div>
                               </div>
                                <div style="padding-top:5px;">
                                    <?php 
                                    if($software['Attachment']['type']=='ipa'){
                                        ?><a href="itms-services://?action=download-manifest&url=https://<?php echo env('HTTP_HOST');?>/files/userfiles/<?php echo ($software['Attachment']['plists']); ?>"  class="Button" >点击安装</a>
                                    <?php }else{    

                                        echo $this->Html->link('点击下载', '/files/userfiles/'. $software['Attachment']['path'], array('class'=>"Button",'target' => '_blank')); 
                                    }
                                    ?>
                                    <?php if($software['Software']['parent_id']): ?>
                                    <a href="/Downloads/lists/<?php echo h($software['Software']['parent_id']); ?>/2"  style="color:blue;font-size:100%">历史版本</a>
                                <?php endif; ?>
                                </div>
                            </td>
                    </tr>
                </tbody>
        </table>
    </div>
</div>

<?php endforeach; ?>
<script type="text/javascript" charset="utf-8">
$(function () {
            // Tabs
//            $('#tabs').tabs();
            
            $(".btnShow").click(
                            function() {
                                            //document.getElementById("div_text").className="div_show";
                                            $(this).parent().find("#div_text").show("normal");
                                            $(this).hide();
                                            $(this).next().show();
                    });

                    $(".btnHide").click(
                            function() {
                                            //document.getElementById("div_text").className="div_hide";
                                            $(this).parent().find("#div_text").hide("normal");
                                            $(this).hide();
                                            $(this).prev().show();
                    });

                    $("#div_text").hide("normal");
                    $(".btnShow").show();
                    $(".btnHide").hide();
    
        });
</script>                