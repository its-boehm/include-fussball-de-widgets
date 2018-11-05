( function ( wp ) {
  'use strict';

  /**
   * Registers a new block provided a unique name and an object defining its behavior.
   * @see https://github.com/WordPress/gutenberg/tree/master/blocks#api
   */
  const registerBlockType = wp.blocks.registerBlockType;

  /**
   * Returns a new element of given type. Element is an abstraction layer atop React.
   * @see https://github.com/WordPress/gutenberg/tree/master/element#element
   */
  const el = wp.element.createElement;

  /**
   * Retrieves the translation of text.
   * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
   */
  const __ = wp.i18n.__;

  /**
   * Every block starts by registering a new block type definition.
   * @see https://wordpress.org/gutenberg/handbook/block-api/
   */
  registerBlockType( 'include-fussball-de-widgets/fubade', {
    /**
     * This is the display title for your block, which can be translated with `i18n` functions.
     * The block inserter will show this name.
     */
    title: __( 'Include Fussball.de Widgets' ),

    /**
     * Blocks are grouped into categories to help users browse and discover them.
     * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
     */
    category: 'widgets',

    /**
     * Optional block extended support features.
     */
    supports: {
      html: false
    },

    /**
     * The Attributes for the shortcode block
     */
    attributes: {
      id: {
        type: 'string'
      },
      api: {
        type: 'string'
      },
      notice: {
        type: 'string'
      }
    },

    /**
     * The edit function describes the structure of your block in the context of the editor.
     * This represents what the editor will render when the block is used.
     * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
     *
     * @param {Object} [props] Properties passed from the editor.
     * @return {Element}       Element to render.
     */
    edit( props ) {
      // TODO: create the edit fields for the block
      return el( 'p',
        {
          className: props.className
        },
        __( 'Hello from the editor!' ) );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into `post_content`.
     * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
     *
     * @return {Element}       Element to render.
     */
    save() {
      // TODO: create the save property for the block
      return el( 'p', {
      }, __( 'Hello from the saved content!' ) );
    }
  } );
} )( window.wp );
