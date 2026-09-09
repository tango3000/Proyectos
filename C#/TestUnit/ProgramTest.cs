﻿using Xunit;

namespace TestUnit
{
    public class OldPhonePadConverterTests
    {
        private readonly OldPhonePadConverter converter = new OldPhonePadConverter();


// -------------------------------------------------------------------
        // Basic cases
        [Theory]
        [InlineData("2#", "A")]
        [InlineData("22#", "B")]
        [InlineData("222#", "C")]
        [InlineData("2222#", "A")] // wrap-around
        [InlineData("23#", "AD")]
        [InlineData("2233#", "BE")]
        [InlineData("4433555 555666#", "HELLO")]
        public void Keycvert_BasicCases_ReturnsExpected(string input, string expected)
        {
            var result = converter.Keycvert(input);
            Assert.Equal(expected, result);
        }


//-------------------------------------------------------------------
        
        // Cases with asterisk (*)
        [Theory]
        [InlineData("22*#", "")]
        [InlineData("222*#", "A")]
        [InlineData("22*22#", "B")]
        [InlineData("222333*#", "AD")]
        [InlineData("222*333#", "D")]
        public void Keycvert_AsteriskCases_ReturnsExpected(string input, string expected)
        {
            var result = converter.Keycvert(input);
            Assert.Equal(expected, result);
        }


//-------------------------------------------------------------------
        // Cases with spaces
        [Theory]
        [InlineData("22 22#", "AA")]
        [InlineData("22 2#", "A A")]
        [InlineData("4433555 555666#", "HELLO")]
        public void Keycvert_SpaceCases_ReturnsExpected(string input, string expected)
        {
            var result = converter.Keycvert(input);
            Assert.Equal(expected, result);
        }


//--------------------------------------------------------------------
        // Edge cases
        [Theory]
        [InlineData("#", "")]
        [InlineData("*#", "")]
        [InlineData(" #", "")]
        [InlineData("2222222222#", "A")]
        [InlineData("2 2 2#", "A A A")]
        public void Keycvert_EdgeCases_ReturnsExpected(string input, string expected)
        {
            var result = converter.Keycvert(input);
            Assert.Equal(expected, result);
        }


//--------------------------------------------------------------------
        // Invalid cases
        [Theory]
        [InlineData("", "")]
        [InlineData(null, "")]
        [InlineData("22", "")]
        [InlineData("1#", "")]
        [InlineData("A23#", "D")]
        [InlineData("23B#", "AD")]
        public void Keycvert_InvalidCases_ReturnsExpected(string input, string expected)
        {
            var result = converter.Keycvert(input);
            Assert.Equal(expected, result);
        }


//--------------------------------------------------------------------
        // Complex cases
        [Theory]
        [InlineData("8 88777444666*664#", "TURING")]
        [InlineData("7777 7777666*6633#", "PROGRAM")]
        [InlineData("2*2#", "A")]
        [InlineData("22 2*#", "A")]
        [InlineData("227*#", "")]
        public void Keycvert_ComplexCases_ReturnsExpected(string input, string expected)
        {
            var result = converter.Keycvert(input);
            Assert.Equal(expected, result);
        }
//--------------------------------------------------------------------

        // New test cases for '1' (special characters) and '0' (space)
        [Theory]
        [InlineData("1#", "!")]
        [InlineData("11#", "@")]
        [InlineData("111#", "#")]
        [InlineData("1111#", "!")] // wrap-around
        [InlineData("101#", "! !")]
        [InlineData("0#", " ")]
        [InlineData("00#", "  ")]
        [InlineData("010#", " ! ")]
        public void Keycvert_SpecialAndSpaceCases_ReturnsExpected(string input, string expected)
        {
            var result = converter.Keycvert(input);
            Assert.Equal(expected, result);
        }

    }
}
