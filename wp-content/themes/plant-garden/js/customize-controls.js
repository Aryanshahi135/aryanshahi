( function( api ) {

	// Extends our custom "plant-garden" section.
	api.sectionConstructor['plant-garden'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );