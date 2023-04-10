<?php

namespace App\Core\Domain\Models;

use App\Core\Domain\Models\User\UserId;
use Exception;
use ReflectionClass;

/**
 * untuk class Sertifikat ini bila ada penambahan properties cukup bikin property barunya dan setter getter nya
 * dan jangan lupa juga untuk menambahkan kolom di migration bila ada properti baru
 * untuk bikin setter dan getternya harus dari shortcut phpstorm
 */
class Sertifikat
{
    private UserId $user_id;
    private ?string $nlc_penyisihan;

    /**
     * Pakai fungsi ini untuk construct di infrastructure
     * class Sertif ini tidak di tujukan untuk di construct di tempat lain
     * maka dari itu gunakan static method ini untuk mengonstruct di infrastructure
     * @throws Exception
     */
    public static function constructFromObject(object $object): self
    {
        $sertif = new self();
        /**
         * ReflectionClass akan menampilkan detail properties, method, dll dari class ini
         */
        foreach ((new ReflectionClass(self::class))->getMethods() as $method) {

            /**
             * kita akan mengisi properties dari class Sertif ini dari parameter object
             * attribute object akan di akses dari nama variabel dari parameter setiap setter
             * maka dari itu nama kolom db harus sama dengan nama variabel dari parameter setter
             */
            if (str_contains($method_name = $method->getName(), 'set')) {
                $param = $method->getParameters()[0]->getName();
                /**
                 * ini kalo tipe datanya cuma string tinggal dimasukin aja
                 * kalo tipe datanya class harus dibuat instance class tersebut
                 */
                if ('string' != $type = $method->getParameters()[0]->getType()->getName()) {
                    $parameter = new $type($object->$param);
                } else $parameter = $object->$param ?? null;

                $sertif->$method_name($parameter);
            }
        }
        return $sertif;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->user_id;
    }

    /**
     * @param UserId $user_id
     */
    public function setUserId(UserId $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string|null
     */
    public function getNlcPenyisihan(): ?string
    {
        return $this->nlc_penyisihan;
    }

    /**
     * @param string|null $nlc_penyisihan
     */
    public function setNlcPenyisihan(?string $nlc_penyisihan): void
    {
        $this->nlc_penyisihan = $nlc_penyisihan;
    }
}
