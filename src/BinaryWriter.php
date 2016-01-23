<?php

namespace PhpBinaryReader;

use PhpBinaryReader\Exception\InvalidDataException;
use PhpBinaryReader\Type\Bit;
use PhpBinaryReader\Type\Byte;
use PhpBinaryReader\Type\Int8;
use PhpBinaryReader\Type\Int16;
use PhpBinaryReader\Type\Int32;
use PhpBinaryReader\Type\String;

class BinaryWriter
{
    /**
     * @var int
     */
    private $machineByteOrder = Endian::ENDIAN_LITTLE;

    /**
     * @var resource 
     */
    private $inputHandle;

    /**
     * @var int
     */
    private $currentBit;

    /**
     * @var mixed
     */
    private $nextByte;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $eofPosition;

    /**
     * @var string
     */
    private $endian;

    /**
     * @var \PhpBinaryWriter\Type\Byte
     */
    private $byteWriter;

    /**
     * @var \PhpBinaryWriter\Type\Bit
     */
    private $bitWriter;

    /**
     * @var \PhpBinaryWriter\Type\String
     */
    private $stringWriter;

    /**
     * @var \PhpBinaryWriter\Type\Int8
     */
    private $int8Writer;

    /**
     * @var \PhpBinaryWriter\Type\Int16
     */
    private $int16Writer;

    /**
     * @var \PhpBinaryWriter\Type\Int32
     */
    private $int32Writer;

    /**
     * @param  resource           $output
     * @param  int|string                $endian
     * @throws \InvalidArgumentException
     */
    public function __construct($output, $endian = Endian::ENDIAN_LITTLE)
    {
        $this->setEndian($endian);
		$this->inputHandle = $output;
        $this->bitWriter = new Bit();
        $this->stringWriter = new String();
        $this->byteWriter = new Byte();
        $this->int8Writer = new Int8();
        $this->int16Writer = new Int16();
        $this->int32Writer = new Int32();
    }

    /**
     * @return void
     */
    public function align()
    {
        $this->setCurrentBit(0);
        $this->setNextByte(false);
    }


    /**
     * @param  byte[] $bytes
     * @return int
     */
    public function writeBytes($bytes)
    {
        return $this->byteWriter->write($this, $bytes);
    }

    /**
     * @return string
     */
    public function writeUInt16($value)
    {
        return $this->int16Writer->write($this, $value);
    }

    /**
     * @return int
     */
    public function writeInt32($value)
    {
        return $this->int32Writer->readSigned($this, $value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function writeString($value)
    {
        return $this->stringWriter->write($this, $value);
    }


    /**
     * @param  int   $machineByteOrder
     * @return $this
     */
    public function setMachineByteOrder($machineByteOrder)
    {
        $this->machineByteOrder = $machineByteOrder;

        return $this;
    }

    /**
     * @return int
     */
    public function getMachineByteOrder()
    {
        return $this->machineByteOrder;
    }

    /**
     * @param  resource $inputHandle
     * @return $this
     */
    public function setInputHandle($inputHandle)
    {
        $this->inputHandle = $inputHandle;

        return $this;
    }

    /**
     * @return resource
     */
    public function getInputHandle()
    {
        return $this->inputHandle;
    }


    /**
     * @param  string               $endian
     * @return $this
     * @throws InvalidDataException
     */
    public function setEndian($endian)
    {
        if ($endian == 'big' || $endian == Endian::ENDIAN_BIG) {
            $this->endian = Endian::ENDIAN_BIG;
        } elseif ($endian == 'little' || $endian == Endian::ENDIAN_LITTLE) {
            $this->endian = Endian::ENDIAN_LITTLE;
        } else {
            throw new InvalidDataException('Endian must be set as big or little');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getEndian()
    {
        return $this->endian;
    }

    /**
     * @param  int   $currentBit
     * @return $this
     */
    public function setCurrentBit($currentBit)
    {
        $this->currentBit = $currentBit;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentBit()
    {
        return $this->currentBit;
    }

    /**
     * @return \PhpBinaryWriter\Type\Bit
     */
    public function getBitWriter()
    {
        return $this->bitWriter;
    }

    /**
     * @return \PhpBinaryWriter\Type\Byte
     */
    public function getByteWriter()
    {
        return $this->byteWriter;
    }

    /**
     * @return \PhpBinaryWriter\Type\Int8
     */
    public function getInt8Writer()
    {
        return $this->int8Writer;
    }

    /**
     * @return \PhpBinaryWriter\Type\Int16
     */
    public function getInt16Writer()
    {
        return $this->int16Writer;
    }

    /**
     * @return \PhpBinaryWriter\Type\Int32
     */
    public function getInt32Writer()
    {
        return $this->int32Writer;
    }

    /**
     * @return \PhpBinaryWriter\Type\String
     */
    public function getStringWriter()
    {
        return $this->stringWriter;
    }

    /**
     * Read a length of characters from the input handle, updating the
     * internal position marker.
     *
     * @return string
     */
    public function readFromHandle($length)
    {
        $this->position += $length;
        return fread($this->inputHandle, $length);
    }
}