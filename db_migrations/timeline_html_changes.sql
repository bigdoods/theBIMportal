UPDATE  `portal_bimscript`.`case` SET  `text` =  '<li data-notif="[+notif_id+]">

  <div class="image"><div><img src="%1$s" alt="" /></div></div>                                     
  <div class="left_con_del">
    <h2>%2$s</h2>
    <div class="clear"></div>
    <p>Has uploaded a file named <strong>%3$s</strong> to the project <strong>%4$s</strong> of type <strong>%5$s</strong></p>
    <span class="date">[+datetime+]</span>
    <div class="actions">
      <a id="pid-[+pid+]" href="[+filelink+]" class="for_admin_ajax set_project blue-button action">View File</a>
      <a href="[+ticketlink+]" class="for_admin_ajax set_project blue-button action" id="pid-[+pid+]">View Ticket</a>
    </div>
  </div>
                                        
</li>' WHERE  `case`.`id` =3;
UPDATE  `portal_bimscript`.`case` SET  `text` =  '<li data-notif="[+notif_id+]">
  <div class="image"><div><img src="%1$s" alt="" /></div></div>
  <div class="left_con_del">
    <h2>%2$s</h2>
    <div class="clear"></div>
    <p>Has been revoked from the project <strong>%3$s</strong></p>
    <span class="date">[+datetime+]</span>
  </div>
</li>' WHERE  `case`.`id` =2;
UPDATE  `portal_bimscript`.`case` SET  `text` =  '<li data-notif="[+notif_id+]">
  <div class="image"><div><img src="%1$s" alt="" /></div></div>
  <div class="left_con_del">
    <h2>%2$s</h2>
    <div class="clear"></div>
    <p>Has been assigned to the project <strong>%3$s</strong></p>
    <span class="date">[+datetime+]</span>
  </div>
</li>' WHERE  `case`.`id` =1;
UPDATE  `portal_bimscript`.`case` SET  `text` =  '<li data-notif="[+notif_id+]">
  <div class="image"><div><img src="%1$s" alt="" /></div></div>
  <div class="left_con_del">
    <h2>%2$s</h2>
    <div class="clear"></div>
    <p>Has requested a file of the project <strong>%3$s</strong> of type <strong>%4$s</strong>, and the extension is <strong>%5$s</strong>.</p>
    <span class="date">[+datetime+]</span>
    <div class="actions">
      <a href="[+ticketlink+]" class="for_admin_ajax set_project blue-button action" id="pid-[+pid+]">View Ticket</a>
    </div>
  </div>
</li>' WHERE  `case`.`id` =4;
UPDATE  `portal_bimscript`.`case` SET  `text` =  '<li data-notif="[+notif_id+]">
  <div class="image" style="background-image: url(%5$s);"></div>
  <div class="left_con_del">
    <h2>%2$s</h2>
    <div class="clear"></div>
    <p>An issue has been detected!<span></p>
    <span class="date">[+datetime+]</span>
    <div class="actions">
      <a href="[+ticketlink+]" class="for_admin_ajax set_project blue-button action" id="pid-[+pid+]">View Ticket</a>
    </div>
  </div>
</li>' WHERE  `case`.`id` =5;