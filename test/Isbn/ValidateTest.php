<?php

use Isbn\Isbn\Validate;

class ValidateTest extends PHPUnit_Framework_TestCase {

  public function testCreation() {
    $validate = new Validate();
    $this->assertInstanceOf('Isbn\Isbn\Validate', $validate);
  }

  /**
   * @dataProvider isbnNumbersProvider
   */
  public function testValidation($isbn, $valid) {
    $validate = new Validate();
    $this->assertEquals($valid, $validate->valid($isbn));
  }

  public function isbnNumbersProvider() {
    return array(
      array('9780552167758', true),
      array('9781743007419', true),
      array('9780857633026', true),
      array('9780752488110', true),
      array('9781848317260', true),
      array('9781607069676', true),
      array('9781781855898', true),
      array('9780007424832', true),
      array('9781444000177', true),
      array('9780723292098', true),
      array('9780723288589', true),
      array('9780857534057', true),
      array('9780099580867', true),
      array('9780670919635', true),
      array('9780007502530', true),
      array('9781471131509', true),
      array('9780141187761', true),
      array('9781780224251', true),
      array('9780751548303', true),
      array('9780141355078', true),
      array('9780552167055', true),
      array('9780752898490', true),
      array('9781447208679', true),
      array('9781405909860', true),
      array('9781743463062', true),
      array('9781782690269', true),
      array('9781857886153', true),
      array('9781843548928', true),
      array('9781908745439', true),
      array('9781908745361', true),
      array('9781781082713', true),
      array('9780957185326', true),
      array('9780141352763', true),
      array('9780415831109', true),
      array('9780007574568', true),
      array('9780141341569', true),
      array('9781471117671', true),
      array('9780091951528', true),
      array('9781405913539', true),
      array('9780241968970', true),
      array('9780860685111', true),
      array('9781782111542', true),
      array('9780099581161', true),
      array('9781849908207', true),
      array('9781848665361', true),
      array('9780241004180', true),
      array('9781444742732', true),
      array('9780571317165', true),
      array('9780241970256', true),
      array('9780007461776', true),
      array('9780755355938', true),
      array('9780099556039', true),
      array('9781471125843', true),
      array('9781409145974', true),
      array('9780349139630', true),
      array('9780099571353', true),
      array('9780241003503', true),
      array('9781471125874', true),
      array('9780718178765', true),
      array('9781780890272', true),
      array('9780230760585', true),
      array('9781444788624', true),
      array('9781782067627', true),
      array('9780857203076', true),
      array('9781847154408', true),
      array('9781847397621', true),
      array('9780785154624', true),
      array('9781421576947', true),
      array('9780007300884', true),
      array('9781444009460', true),
      array('9780141346090', true),
      array('9780141346113', true),
      array('9781406332933', true),
      array('9781444754513', true),
      array('9781780722153', true),
      array('9780753555101', true),
      array('9780356500904', true),
      array('9780007551255', true),
      array('9781405909976', true),
      array('9780749958121', true),
      array('9780857632685', true),
      array('9781783120369', true),
      array('9781849493734', true),
      array('9781401250355', true),
      array('9781846535857', true),
      array('9781401246020', true),
      array('9781846275104', true),
      array('9781849706100', true),
      array('9780141353258', true),
      array('9781405914130', true),
      array('9780091958312', true),
      array('9780552772563', true),
      array('9781444799569', true),
      array('9781780891910', true),
      array('9780857832238', true),
      array('9781471129919', true),
      array('9781447261667', true),
      array('9780091944001', true),
      array('9781849495042', true),
      array('9781444794328', true),
      array('9780099558293', true),
      array('9781447267485', true),
      array('9780552779326', true),
      array('9780755358984', true),
      array('9780091956097', true),
      array('9780007331925', true),
      array('9780099574156', true),
      array('9781444761184', true),
      array('9781409120346', true),
      array('9780552169325', true),
      array('9780434022922', true),
      array('9781401245085', true),
      array('9780007590063', true),
      array('9780007532575', true),
      array('9780007499847', true),
      array('9780593069837', true),
      array('9781408704790', true),
      array('9781782062080', true),
      array('9781471403989', true),
      array('9781781802915', true),
      array('9781607069621', true),
      array('9781401245092', true),
      array('9781407124407', true),
      array('9781405235815', true),
      array('9781405235518', true),
      array('9780230753105', true),
      array('9781444921182', true),
      array('9780224092074', true),
      array('9780091957841', true),
      array('9780593073995', true),
      array('9780007564286', true),
      array('9780241959596', true),
      array('9780241146699', true),
      array('9781846044199', true),
      array('9781846147555', true),
      array('9781602862647', true),
      array('9780263246513', true),
      array('9780263246483', true),
      array('9780141182704', true),
      array('9781782068372', true),
      array('9780099578970', true),
      array('9780356501109', true),
      array('9781444706499', true),
      array('9780241963388', true),
      array('9780755353828', true),
      array('9781408853580', true),
      array('9781783120352', true),
      array('9781780722245', true),
      array('9780712357166', true),
      array('9781846535840', true),
      array('9781849706056', true),
      array('9781447268659', true),
      array('9781405273527', true),
      array('9781405235570', true),
      array('9781405235327', true),
      array('9781405251426', true),
      array('9781407138879', true),
      array('9781444776775', true),
      array('9780099559054', true),
      array('9781447273349', true),
      array('9780007519743', true),
      array('9781444780086', true),
      array('9781849493659', true),
      array('9781845339104', true),
      array('9780007485871', true),
      array('9781471134579', true),
      array('9780753555026', true),
      array('9781781855768', true),
      array('9781780871240', true),
      array('9780751549591', true),
      array('9780007544813', true),
      array('9780552779005', true),
      array('9780099559283', true),
      array('9781847562821', true),
      array('9781405914871', true),
      array('9780552160964', true),
      array('9780099580881', true),
      array('9780552169585', true),
      array('9780230757639', true),
      array('9780330454223', true),
      array('9780755357024', true),
      array('9781780892337', true),
      array('9781444757453', true),
      array('9781910114162', true),
      array('9781782392569', true),
      array('9781781852699', true),
      array('9781846271137', true),
      array('9781414391397', true),
      array('9781607069461', true),
      array('9780440870692', true),
      array('9781405235242', true),
      array('9781405235174', true),
      array('9781405235709', true),
      array('9781405235266', true),
      array('9781405235648', true),
      array('9781405235259', true),
      array('9780141340067', true),
      array('9781405235617', true),
      array('9781471129483', true),
      array('9781405918237', true),
      array('9780091947309', true),
      array('9780857831026', true),
      array('9781408836453', true),
      array('9780749958763', true),
      array('9781408842454', true),
      array('9780224087889', true),
      array('9780857053091', true),
      array('9781447256779', true),
      array('9780718177058', true),
      array('9781444786323', true),
      array('9780099585152', true),
      array('9780755378494', true),
      array('9788889527190', false),
      array('97888895271910', false),
      array('badger', false),
      array(false, false),
      array(1, false),
      array('8881837188', true),
      array('8881837187', false),
      array('9788889527191', true),
      array('ISBN', false)
    );
  }

  /**
   * @dataProvider isbn10NumbersProvider
   */
  public function testIsbn10($isbn, $valid) {


    $validate = new Validate();
    $this->assertEquals($valid, $validate->validateIsbn10($isbn));
  }
  
  public function isbn10NumbersProvider() {
    return array(
      array('9780552167758', false),
      array('9781743007419', false),
      array('9780857633026', false),
      array('9780752488110', false),
      array('9781848317260', false),
      array('9781607069676', false),
      array('9781781855898', false),
      array('9780007424832', false),
      array('9781444000177', false),
      array('9780723292098', false),
      array('9780723288589', false),
      array('9780857534057', false),
      array('9780099580867', false),
      array('9780670919635', false),
      array('9780007502530', false),
      array('9781471131509', false),
      array('9780141187761', false),
      array('1444786326', true),
      array('1743007418', true),
      array('0857633023', true),
      array('0752488112', true),
      array('1848317263', true),
      array('1607069679', true),
      array('0007424833', true),
      array('0670919632', true), 
      array('014118776X', true)
    );
  }
  
  /**
   * @dataProvider isbn13NumbersProvider
   */
  public function testIsbn13($isbn, $valid) {    
    $validate = new Validate();
    $this->assertEquals($valid, $validate->validateIsbn13($isbn));    
  }

  public function isbn13NumbersProvider() {
    return array(
      array('9780552167758', true),
      array('9781743007419', true),
      array('9780857633026', true),
      array('9780752488110', true),
      array('9781848317260', true),
      array('9781607069676', true),
      array('9781781855898', true),
      array('9780007424832', true),
      array('9781444000177', true),
      array('9780723292098', true),
      array('9780723288589', true),
      array('9780857534057', true),
      array('9780099580867', true),
      array('9780670919635', true),
      array('9780007502530', true),
      array('9781471131509', true),
      array('9780141187761', true),
      array('1444786326', false),
      array('1743007418', false),
      array('0857633023', false),
      array('0752488112', false),
      array('1848317263', false),
      array('1607069679', false),
      array('0007424833', false),
      array('0670919632', false), 
      array('014118776X', false)
    );
  }
}
