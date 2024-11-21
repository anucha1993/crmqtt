<?php

namespace App\Helpers;

class BathTextHelper
{
               public static function convert($number)
               {
                              // กำหนดค่าขั้นต้น
                              $bahtText = '';

                              // กำหนดการแปลงตัวเลขเป็นข้อความ
                              $numberStr = number_format($number, 2, '.', '');
                              list($baht, $satang) = explode('.', $numberStr);

                              $bahtText = self::readNumber($baht) . 'บาท';
                              if ($satang > 0) {
                                             $bahtText .= self::readNumber($satang) . 'สตางค์';
                              } else {
                                             $bahtText .= 'ถ้วน';
                              }

                              return $bahtText;
               }

               private static function readNumber($number)
               {
                              $units = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
                              $levels = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];

                              $number = strval(intval($number));
                              $bahtText = '';
                              $numberLength = strlen($number);

                              for ($i = 0; $i < $numberLength; $i++) {
                                             $digit = $number[$numberLength - $i - 1];
                                             $unit = $units[$digit];
                                             $level = $levels[$i % 6];

                                             if ($digit == 1 && $i % 6 == 1) {
                                                            $unit = 'สิบ';
                                             } elseif ($digit == 2 && $i % 6 == 1) {
                                                            $unit = 'ยี่สิบ';
                                             } elseif ($digit == 1 && $i % 6 == 0 && $i > 0) {
                                                            $unit = 'เอ็ด';
                                             }

                                             if ($unit !== '') {
                                                            $bahtText = $unit . $level . $bahtText;
                                             }
                              }

                              return $bahtText;
               }
}
