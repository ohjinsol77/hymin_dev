<?php
include("../_inc/header.php");


?>
    <html>
    <body>
    <h2>회원 등록</h2>
    <hr width="80%"/>
    <div id="#contsRow">

        <form name="memRegForm" method="post" action="mem_regOk.php" class="formtag">

            <ul style="list-style-type:square">

                <li><label for="strId">id</label> <input type="text" name="strId"
                                                         class="strId" autofocus/></li>

                <li><label for="strName">이름</label> <input type="text"
                                                           name="strName" class="strName"/></li>

                <li><label for="strPassword">비밀번호</label> <input type="password"
                                                                 name="strPassword" class="strPassword"/></li> &nbsp;&nbsp;&nbsp;
                <li><label for="strPassword">비밀번호확인</label> <input type="password"
                                                                   name="strPassword_check"/></li>

                <li><label for="nSex">성별</label>
                    남<input type="radio" name="nSex" value="1"/>
                    여<input type="radio" name="nSex" value="2"/>
                    ???<input type="radio" name="nSex" value="3"/>
                </li>

                <li><label for="strTel">전화번호</label> <input type="number" name="strTel" class="strTel"/></li>

                <li><label for="strAdd">주소</label> <input type="text" name="strAdd" class="strAdd" size="50"/></li>
            </ul>
            <ul>
                <hr width="80%"/>

                <li align="center"><input type="submit" value="등록하기"/><input type="reset" value="다시작성하기"/></li>
            </ul>

        </form>
    </div>


    </body>
    </html>
<?php
include("../_inc/footer.php");