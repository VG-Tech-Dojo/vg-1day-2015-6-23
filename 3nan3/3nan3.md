### ���e���œV�C�𕷂��i�l�ȓ��e������j�Ɨ\�񂪓��e�����
(update:2015/06/24)

�C���[�W

> �����̓����̓V�C�́H

�Ɠ��e����ƁA

>> �����̓����̓V�C�͓܂�̂�����ł�

�����b�Z�[�W�Ƃ��Ďc��悤�ɂȂ��Ă��܂��B

* �ڍא���

 �󗝂��郁�b�Z�[�W��`(�����E�����E������E[��]��)([�n��]��)�V�C�́H`  
 �n����������Ȃ���΁iDB�ɓo�^����ĂȂ���΁j�\��͂ł��Ȃ��悤�ɂȂ��Ă��܂�  
 ���t�ō����E�����E������ȊO���w�肳���Ƃ��̒n��̗\��T�v�������e����܂�  
 ���t�ƒn���͏����t�ł��󗝂ł���悤�ɂȂ��Ă��܂��B�܂��A"��"�^"�H"�͏ȗ��ł�  
 
 �V�C�\��̏���Livedoor�̂��V�CAPI�������Ă��Ă܂�
 http://weather.livedoor.com/forecast/webservice/json/v1?city=400040
 
* �R�[�h�̗v��

 ���b�Z�[�W���󗝂��邩�ǂ����͎��̂悤�ɒ��ׂĂ��܂�(app.php:27)  
 `preg_match('/^(.+��){1,2}�V�C(�́H?)?$/u', $body)`  
 
 API�ł͒n����w�肷�邽�߂�ID���K�v�ƂȂ�A���O��DB�ɒn����ID��o�^���Ă��������̂���n�����w�肵��ID���擾���Ă��܂�  
 �����A�O�q�̂Ƃ����ɒn�������邩���t�����邩�킩��Ȃ��̂ŁA�n�����}�b�`���邩���m���߂Ă��猈�肵�Ă��܂��B(Application.php:176-189)
 `
        $split = preg_split("/��/u", $message, -1, PREG_SPLIT_NO_EMPTY);
        $cityObj = $this['repository.weather']->getCity($split[0]);
		if($cityObj != null) {
			$dateLabel = $split[1];
		}
		else {
			$cityObj = $this['repository.weather']->getCity($split[1]);
			$dateLabel = $split[0];
			if($cityObj == null) {
				return '������Ƃ킩��Ȃ������ˁ[';
			}
		}`
 
 �n����ID�̌��ѕt���͈ȉ��Q�ƂƂ̎��ł����B  
 http://weather.livedoor.com/forecast/rss/primary_area.xml  

 