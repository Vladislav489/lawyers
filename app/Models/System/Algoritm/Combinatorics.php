<?php
namespace App\Models\System\Algoritm;

class Combinatorics {
/*
 * const array = ['a', 'b', 'c', 'd', 'e']; // множество элементов
const k = 4; // размер сочетаний

const combinations = combine2(array, k);
console.log("итого:", combinations);

function combine2(array, k) {
  const n = array.length - 1; // максимальный индекс массива элементов
  const m = k - 1; // максимальный индекс массива-маски сочетания
  const finds = []; // массив всех возможных осчетаний
  const mask = []; // маска сочетания
  let finish = false;
  for (let i = 0; i < k; i++) mask.push(array[i]);
  while (!finish) {
    finish = true;
    const str = mask.join('');
    if (!finds.includes(str)) finds.push(str); // записываем сочетание в массив
    for (let i = 0; i < k; i++) {
      if (mask[m - i] != array[n - i]) {
        // проверяем, остались ли еще сочетания
        finish = false;
        let p = array.indexOf(mask[m - i]);
        mask[m - i] = array[++p]; // изменяем маску, начиная с последнего элемента
        for (let j = m - i + 1; j < k; j++) {
          mask[j] = array[++p];
        }
        break;
      }
    }
  }
  return finds;
}
 *
 */


}
